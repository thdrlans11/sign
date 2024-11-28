<?php

namespace App\Services\Board;

use App\Models\Board;
use App\Models\BoardCounter;
use App\Models\BoardFile;
use App\Models\BoardPopup;
use App\Services\dbService;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class BoardService
 * @package App\Services
 */
class BoardService extends dbService
{
    private $code;
    
    public function __construct()
    {
        $this->code = request()->code;
    }

    public function list(Request $request)
    {
        $notice = Board::where('code', $this->code)->where('notice', 'Y')->withCount('files')->orderByDesc('created_at')->get();
        $lists = Board::where('code', $this->code)->where('notice', 'N')->withCount('files');

        if( config('site.board')[$this->code]['UseHide'] && !isAdminCheck() ){
            $notice = $notice->where('hide', 'N');
            $lists = $lists->where('hide', 'N');
        }

        foreach( $request->all() as $key => $val ){
            if( ( $key == 'keyword' ) && $val ){
                $lists->where($request->keyfield, 'LIKE', '%'.$val.'%');           
            }
        }

        if( $this->code == 'schedule' ){
            $year = $request->get('year') ?? Carbon::now()->format('Y');
            $month = $request->get('month') ?? null;
            $date = [
                'year' => $year,
                'month' => $month,
            ];

            if( $year && $month ){
                $lists->whereRaw('substr(sdate,1,7)= \''.($year.'-'.$month).'\'');
            }else{
                $lists->whereRaw('substr(sdate,1,4)= \''.$year.'\'');
            }            

            $lists->orderByDesc('sdate');
        }else{
            $lists->orderByDesc('fid')->orderBy('thread');
        }


        $lists = $lists->paginate('10')->appends($request->query());
        $lists = setListSeq($lists);
        $data['lists'] = $lists;
        $data['notice'] = $notice;
        $data['code'] = $this->code;
        $data['date'] = $date ?? [];

        return $data;
    }

    public function calendar(Request $request)
    {
        $search_date = $request->get('search_date') ?? Carbon::now()->toDateString();

        $date = [
            'year' => Carbon::parse($search_date)->format('Y'),
            'month' => Carbon::parse($search_date)->format('m'),
            'prev_date' => Carbon::parse($search_date)->subMonth(1)->startOfMonth()->toDateString(),
            'next_date' => Carbon::parse($search_date)->addMonth(1)->startOfMonth()->toDateString(),
            'start_date' => Carbon::parse($search_date)->startOfMonth()->toDateString(), //시작일
            'end_date' => Carbon::parse($search_date)->endOfMonth()->toDateString(), // 종료일           
        ];

        for( $i=$date['start_date']; $i<=$date['end_date']; $i++ ){

            $calendar = Board::where('code', $this->code)
                             ->whereRaw('substr(sdate,1,10) <= \''.$i.'\'')
                             ->where(function ($query) use($i) {
                               $query->whereDate('sdate',$i)->orWhereRaw('substr(edate,1,10) >= \''.$i.'\''); 
                             })->orderBy('sdate')->get();
                           
            $calendars[$i] = $calendar;

        }

        if( $request->this_day ){
            $lists = Board::where('code', $this->code)
                            ->whereRaw('substr(sdate,1,10) <= \''.$request->this_day.'\'')
                            ->where(function ($query) use($request) {
                            $query->whereDate('sdate',$request->this_day)->orWhereRaw('substr(edate,1,10) >= \''.$request->this_day.'\''); 
                            })->orderBy('sdate')->get();
        }else{
            $lists = Board::where('code', $this->code)->whereRaw('substr(sdate,1,7)= \''.Carbon::parse($search_date)->format('Y-m').'\'')->orderBy('sdate')->get();
        }

        $data['code'] = $this->code;
        $data['date'] = $date;
        $data['calendars'] = $calendars;
        $data['lists'] = $lists;

        return $data;
    }

    public function form(Request $request)
    {
        if( $request->sid ){
            $board = Board::find(base64_decode($request->sid));
            $data['data'] = $board;
            $data['popup'] = $board->popupTarget;
            $data['files'] = $board->files;
        }else{
            $data['data'] = [];   
        }
        $data['code'] = $this->code;

        return $data;
    }

    public function upsert(Request $request)
    {
        $this->transaction();
        
        try {

            if( $request->sid ){               

                $board = Board::find(base64_decode($request->sid));
                $board->setByPost($request);
                $board->save();

                foreach ($board->files()->whereIn('sid', $request->del_file ?? [])->get() as $plFile) {
                    $plFile->delete();
                }

                //팝업 raw가 있는데 팝업을 사용안하면 raw 삭제처리
                if( $board->popupTarget && $request->popup != 'Y' ){
                    $board->popupTarget->delete();
                }

            }else{

                $board = new Board();
                $board->setByPost($request);
                $board->save();
                $board->fid = $board->sid;
                $board->save();

            }

            //팝업 insert
            if( config('site.board')[$this->code]['UsePopup'] && $request->popup == 'Y' ){
                if( !$board->popupTarget ){
                    $popup = new BoardPopup();
                }else{
                    $popup = $board->popupTarget;
                }
                $popup->setByPost($request, $board->sid);
                $popup->save(); 
            }

            //파일 입력
            if( config('site.board')[$this->code]['UseFile'] && $request->plupload_count > 0 ){
                BoardFile::setByPost($request, $board->sid);
            }

            $this->dbCommit($msg ?? '게시판 '.($request->sid?'수정':'등록')); 
            
            return redirect()->route('board.list', ['code'=>$this->code])->with(['alert'=>($request->sid?'수정':'등록').'되었습니다.']);

        } catch (\Exception $e) {

            return $this->dbRollback($e);

        }
    }

    public function view(Request $request)
    {
        $board = Board::find(base64_decode($request->sid));
        $this->refCounter($request); // 조회수 업데이트

        $data['data'] = $board;
        $data['code'] = $this->code;
        $data['files'] = $board->files;

        return $data;
    }

    public function delete(Request $request)
    {
        $board = Board::find(base64_decode($request->sid));
        $board->delete();

        return redirect()->route('board.list', ['code'=>$this->code])->with(['alert'=>'게시물이 삭제 되었습니다.']);
    }

    public function dbChange(Request $request)
    {   
        $this->transaction();

        try {

            $board = Board::find(base64_decode($request->sid));
            $board[$request->field] = $request->value;
            $board->save();

            $this->dbCommit($msg ?? '게시판 변경'); 
            
            return 'success';

        } catch (\Exception $e) {

            return $this->dbRollback($e, 'ajax');

        }
    }

    private function refCounter(Request $request)
    {
        // ip 기준으로 조회수 하루에 한번씩
        $check = BoardCounter::whereRaw("DATE_FORMAT(created_at, '%Y%m%d') = ?", [now()->format('Ymd')])
            ->where([
                'bsid' => base64_decode($request->sid),
                'ip' => $request->ip()
            ])->exists();


        if (!$check) {
            $boardCounter = new BoardCounter();
            $boardCounter->setByData($request);
            $boardCounter->save();

            $boardCounter->board->increment('ref');
        }
    }
}
