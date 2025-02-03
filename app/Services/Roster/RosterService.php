<?php

namespace App\Services\Roster;

use App\Models\Event;
use App\Models\Roster;
use App\Services\CommonService;
use App\Services\dbService;
use Illuminate\Http\Request;

/**
 * Class RosterService
 * @package App\Services
 */
class RosterService extends dbService
{
    public function list(Request $request)
    {
        $event = Event::where('code', $request->code)->first();

        $lists = Roster::where('code', $event->code)->orderBy('name');

        foreach( $request->all() as $key => $val ){
            if( ( $key == 'name' || $key == 'affiliation' || $key == 'email' || $key == 'memoYN' || $key == 'signYN' )  && $val ){
                if( $key == 'memoYN' ){
                    if( $val == 'Y' ){
                        $lists->whereNotNull('memo');
                    }else{
                        $lists->whereNull('memo');
                    }
                }else if( $key == 'signYN' ){
                    if( $val == 'Y' ){
                        $lists->whereNotNull('filename_fee');
                    }else{
                        $lists->whereNull('filename_fee');
                    }
                }else{
                    $lists->where($key, 'LIKE', '%'.$val.'%');
                }
                
            }
        }

        $lists = $lists->paginate('20')->appends($request->query());
        $lists = setListSeq($lists);
        $data['lists'] = $lists;
        $data['event'] = $event;

        return $data;
    }

    public function form(Request $request)
    {
        if( $request->sid ){
            $roster = Roster::find(decrypt($request->sid));
            $data['roster'] = $roster;
        }else{
            $data['roster'] = [];   
        }

        $data['code'] = $request->code;

        return $data;
    }

    public function upsert(Request $request)
    {
        $this->transaction();              

        try {

            if( $request->sid ){
                $roster = Roster::find(decrypt($request->sid));
                $roster->setByPost($request);
                $roster->save();
            }else{
                $roster = new Roster();
                $roster->setByPost($request);
                $roster->save();
            }

            $this->dbCommit($msg ?? '명단 '.($request->sid?'수정':'등록')); 
            
            return redirect()->back()->withSuccess('명단이 '.($request->sid?'수정':'등록').'되었습니다.')->with('close','Y');

        } catch (\Exception $e) {

            return $this->dbRollback($e);

        }
    }

    public function fileForm(Request $request)
    {
        $event = Event::find(decrypt($request->esid));

        $data['event'] = $event;
        $data['code'] = $request->code;

        return $data;
    }

    public function fileUpsert(Request $request)
    {
        $this->transaction();              

        try {

            $event = Event::find(decrypt($request->esid));

            if( $request->userfile ){
                $file = (new CommonService())->fileUploadService($request->userfile, 'event');
                $event->filename_fee = $file['filename'];
                $event->realfile_fee = $file['realfile'];            
            }
    
            if( $request->delfile ){
                (new CommonService())->fileDeleteService($request->delfile);
            }

            if( $request->userfile2 ){
                $file = (new CommonService())->fileUploadService($request->userfile2, 'event');
                $event->filename_agree = $file['filename'];
                $event->realfile_agree = $file['realfile'];            
            }
    
            if( $request->delfile2 ){
                (new CommonService())->fileDeleteService($request->delfile2);
                if( !$request->userfile2 ){
                    $event->filename_agree = null;
                    $event->realfile_agree = null;            
                }
            }
            
            $event->save();

            $this->dbCommit($msg ?? '파일 수정'); 
            
            return redirect()->back()->withSuccess('파일이 수정 되었습니다.')->with('close','Y');

        } catch (\Exception $e) {

            return $this->dbRollback($e);

        }
    }

    public function dbChange(Request $request)
    {   
        $this->transaction();

        try {

            $roster = Roster::find(decrypt($request->sid));            

            if( $request->field == 'delete' ){
                $roster->delete();
                $msg = '관리자 명단 삭제';
            }else{
                if( $request->field == 'fee' ){
                    (new CommonService())->fileDeleteService($roster->realfile_fee);
                    $roster['filename_fee'] = null;
                    $roster['realfile_fee'] = null;
                    $roster['fee_upload_date'] = null;
                    $msg = '관리자 연자료지급 확인서 삭제';
                }else if( $request->field == 'agree' ){
                    (new CommonService())->fileDeleteService($roster->realfile_agree);
                    $roster['filename_agree'] = null;
                    $roster['realfile_agree'] = null;
                    $roster['agree_upload_date'] = null;
                    $msg = '관리자 강의자료제공 동의서 확인서 삭제';
                }else{
                    $roster[$request->field] = $request->value;
                }
                $roster->save();
            }

            $this->dbCommit($msg ?? '관리자 명단 변경'); 
            
            return 'success';

        } catch (\Exception $e) {

            return $this->dbRollback($e, 'ajax');

        }
    }

    public function memoForm(Request $request)
    {
        $roster = Roster::find(decrypt($request->sid));
        $data['roster'] = $roster;
        $data['code'] = $request->code;

        return $data;
    }    

    public function memo(Request $request)
    {   
        $this->transaction();

        try {

            $roster = Roster::find(decrypt($request->sid));
            $roster->memo = $request->memo;
            $roster->save();

            $this->dbCommit('명단 메모 변경'); 
            
            return redirect()->back()->withSuccess('메모 저장이 완료되었습니다.')->with('close','Y');

        } catch (\Exception $e) {

            return $this->dbRollback($e);

        }
    }

    public function excelUploadForm(Request $request)
    {
        $data['code'] = $request->code;

        return $data;
    }

    public function excelUploadUpsert(Request $request)
    {
        $this->transaction();

        try {

            $result = preg_replace('/\r\n|\r|\n/','',$request->post('result'));
            $ex_result =  explode("✚", $result);

            $failAlready = [];

            $success = 0;

            for($i=1;$i<count($ex_result);$i++){

                $ex_name = explode("|::|",$ex_result[$i]); 
                                    
                if( $ex_name[1] == '' ){ continue; }

                $already = Roster::where('code',$request->code)->where('name',trim($ex_name[1]))->where('email',trim($ex_name[3]))->first();

                if( $already ){
                    array_push($failAlready, $ex_name[1]);                    
                    continue;
                }

                $roster = new Roster();
                $roster->code = $request->code;
                $roster->name = $ex_name[1];
                $roster->affiliation = $ex_name[2];
                $roster->email = $ex_name[3];
                $roster->save();            
                   
                $success++;
    
            }

            if( $success > 0 ){
                $this->dbCommit($msg ?? '명단 엑셀업로드');
            }

            $failAlreadyCnt = count($failAlready ?? []);

            $msg = '총 '.$success.'명 등록 되었습니다.';

            if (!empty($failAlready)) {
                $msg .= '<br>이미 등록된 명단 '.$failAlreadyCnt.'개<br>' . implode(',<br>', $failAlready);
            }

            return redirect()->back()->withSuccess($msg)->with('close','Y');

        } catch (\Exception $e) {

            return $this->dbRollback($e);

        }
    }
}
