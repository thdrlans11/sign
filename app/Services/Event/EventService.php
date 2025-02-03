<?php

namespace App\Services\Event;

use App\Models\Event;
use App\Services\CommonService;
use App\Services\dbService;
use Illuminate\Http\Request;

/**
 * Class EventService
 * @package App\Services
 */
class EventService extends dbService
{
    public function list(Request $request)
    {
        $lists = Event::orderByDesc('created_at');

        foreach( $request->all() as $key => $val ){
            if( ( $key == 'title' || $key == 'company' || $key == 'manager' || $key == 'code' )  && $val ){
                $lists->where($key, 'LIKE', '%'.$val.'%');        
            }
        }

        $lists = $lists->paginate('20')->appends($request->query());
        $lists = setListSeq($lists);
        $data['lists'] = $lists;

        return $data;
    }

    public function form(Request $request)
    {
        if( $request->sid ){
            $event = Event::find(decrypt($request->sid));
            $data['event'] = $event;
        }else{
            $data['event'] = [];   
        }

        return $data;
    }

    public function upsert(Request $request)
    {
        $this->transaction();              

        try {

            if( $request->sid ){

                $event = Event::find(decrypt($request->sid));
                $event->setByPost($request);
                $event->save();

            }else{

                $event = Event::where('code', $request->code)->first();

                if( $event ){
                    return redirect()->back()->withError('행사코드는 중복으로 사용할 수 없습니다.');
                }else{
                    $event = new Event();
                    $event->setByPost($request);
                    $event->save();
                }
                
            }

            $this->dbCommit($msg ?? '행사 '.($request->sid?'수정':'등록')); 
            
            return redirect()->back()->withSuccess('행사가 '.($request->sid?'수정':'등록').'되었습니다.')->with('close','Y');

        } catch (\Exception $e) {

            return $this->dbRollback($e);

        }
    }

    public function dbChange(Request $request)
    {   
        $this->transaction();

        try {

            $event = Event::find(decrypt($request->sid));            

            if( $request->field == 'delete' ){
                $event->delete();
            }else if( $request->field == 'reset' ){

                foreach( $event->rosters as $roster ){
                    if( $roster->realfile_fee ){
                        (new CommonService())->fileDeleteService($roster->realfile_fee);
                        $roster['filename_fee'] = null;
                        $roster['realfile_fee'] = null;
                        $roster['fee_upload_date'] = null;
                    }
                    if( $roster->realfile_agree ){
                        (new CommonService())->fileDeleteService($roster->realfile_agree);
                        $roster['filename_agree'] = null;
                        $roster['realfile_agree'] = null;
                        $roster['agree_upload_date'] = null;
                    }
                    $roster->save();
                }

                $msg = '관리자 명단 리셋';

            }else{
                $event[$request->field] = $request->value;
                $event->save();
            }

            $this->dbCommit($msg ?? '관리자 행사 변경'); 
            
            return 'success';

        } catch (\Exception $e) {

            return $this->dbRollback($e, 'ajax');

        }
    }
}
