<?php

namespace App\Services\Api;

use App\Models\Event;
use App\Models\Roster;
use App\Services\CommonService;
use App\Services\dbService;
use Illuminate\Http\Request;

/**
 * Class SignService
 * @package App\Services
 */
class SignService extends dbService
{
    public function list(Request $request)
    {
        $event = Event::where('authToken', $request->authToken)->first();
        
        $rosters = Roster::where('code', $event->code)->orderBy('name');

        if( !$request->status || $request->status == '0' ){
            $rosters->whereNull('realfile_fee');        
        }else{
            $rosters->whereNotNull('realfile_fee');        
        }

        $rosters = $rosters->get();

        $member = array();

        foreach( $rosters as $index => $roster ){

            if( !$roster->realfile_fee || !$event->realfile_fee ){
                $status1 = '0';
                $date1 = null;
            }else{
                $status1 = '1';
                $date1 = $roster->fee_upload_date->format('Y-m-d, H:i:s');
            }

            if( !$roster->realfile_agree || !$event->realfile_agree ){
                $status2 = '0';
                $date2 = null;
            }else{
                $status2 = '1';
                $date2 = $roster->agree_upload_date->format('Y-m-d, H:i:s');
            }

            array_push( $member, array( 'number'=>$index+1, 'name'=>$roster->name, 'hospital'=>$roster->affiliation, 'status1'=>$status1, 'date1'=>$date1, 'status2'=>$status2, 'date2'=>$date2, 'rosterSid'=>$roster->sid ) );
            
        }        

        return $this->apiResponse(200, [ 'message' => 'success', 'data' => [ 'member' =>  $member  ]  ] );        
    }

    public function file(Request $request)
    {
        $this->transaction();              

        try {

            $event = Event::where('authToken', $request->authToken)->first();
            $roster = Roster::where('sid', $request->rosterSid)->where('code', $event->code)->first();

            if( !$roster ){
                return $this->apiResponse(330, [ 'message' => 'noAccess', 'data' => null  ] );
            }

            if( $request->img ){
                $file = (new CommonService())->fileUploadService($request->img, $request->kind);
                $roster['filename_'.$request->kind] = $file['filename'];
                $roster['realfile_'.$request->kind] = $file['realfile'];           
                $roster[$request->kind.'_upload_date'] = now();
            }

            $roster->save();

            $this->dbCommit($msg ?? 'App fileUpload '.$request->kind); 

            return $this->apiResponse(200, [ 'message' => 'success', 'data' => null  ] ); 
        
        } catch (\Exception $e) {

            return $this->dbRollback($e, 'api');

        }
    }

    public function fileDel(Request $request)
    {
        $this->transaction();              

        try {

            $event = Event::where('authToken', $request->authToken)->first();
            $roster = Roster::where('sid', $request->rosterSid)->where('code', $event->code)->first();

            if( !$roster ){
                return $this->apiResponse(330, [ 'message' => 'noAccess', 'data' => null  ] );
            }

            (new CommonService())->fileDeleteService($roster['realfile_'.$request->kind]);
            
            $roster['filename_'.$request->kind] = null;
            $roster['realfile_'.$request->kind] = null;           
            $roster[$request->kind.'_upload_date'] = null;

            $roster->save();

            $this->dbCommit($msg ?? 'App fileDel '.$request->kind); 

            return $this->apiResponse(200, [ 'message' => 'success', 'data' => null  ] ); 
        
        } catch (\Exception $e) {

            return $this->dbRollback($e, 'api');

        }
    }

    public function signView(Request $request)
    {
        $event = Event::where('authToken', $request->authToken)->first();
        $roster = Roster::where('sid', $request->rosterSid)->where('code', $event->code)->first();

        if( !$roster ){
            return $this->apiResponse(330, [ 'message' => 'noAccess', 'data' => null  ] );
        }else{
            if( $roster['realfile_'.$request->kind] ){
                return $this->apiResponse(200, [ 'message' => 'success', 'data' => [ 'img' => config('site.common.web.url').$roster['realfile_'.$request->kind] ]  ] );
            }else{
                return $this->apiResponse(300, [ 'message' => 'noSignSave', 'data' => null  ] );
            }
        }
    }    

}