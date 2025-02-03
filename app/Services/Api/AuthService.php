<?php

namespace App\Services\Api;

use App\Models\Event;
use App\Services\dbService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService extends dbService
{
    public function auth(Request $request)
    {
        $this->transaction();              

        try {

            $id = $request->id;
            $pw = $request->pw;

            if( !$id || !$pw ){
                return $this->apiResponse(300, [ 'message' => 'valueEmpty', 'data' => null] );
            }

            $event = Event::where('code', $id)->first();

            if( $event && Hash::check($pw, $event['password']) ){

                $event->update([
                    'authToken' => Str::random(35), // secret 토큰
                    'authTokenTime' => now()->addMinutes(360) // 토큰 만료시간 현재시간 부터 30분
                ]);

                $this->dbCommit($msg ?? 'App Login '.$event->sid); 

                return $this->apiResponse(200, [ 'message' => 'success',
                                                 'data' => [
                                                            'sid'=>$event->authToken,
                                                            'img1'=>$event->realfile_fee ? config('site.common.web.url').$event->realfile_fee : null,
                                                            'img2'=>$event->realfile_agree ? config('site.common.web.url').$event->realfile_agree : null,
                                                            'eventName'=>$event->title ] ] );

            }else{

                return $this->apiResponse(300, [ 'message' => 'notMatching', 'data' => null] );

            }

        } catch (\Exception $e) {

            return $this->dbRollback($e, 'api');

        }    
    }

    public function logout(Request $request)
    {
        $this->transaction();

        try {

            $event = Event::where('authToken', $request->authToken)->first();

            $event->update([
                'authToken' => null,
                'authTokenTime' => null
            ]);

            $this->dbCommit($msg ?? 'App Logout '.$event->sid);

            return $this->apiResponse(200, [ 'message' => 'success', 'data' => null] );

        } catch (\Exception $e) {

            return $this->dbRollback($e, 'api');

        } 

    }    

    //미들웨어 토큰인증
    public function authTokenCheck(Request $request)
    {
        $authToken = $request->bearerToken();

        if (!$authToken) {
            return $this->apiResponse(330, [ 'message' => 'noAccess', 'data' => null] );
        }

        $query = Event::where([
            'authToken' => $authToken,
        ]);

        if (!$query->exists()) {
            return $this->apiResponse(330, [ 'message' => 'noAccess', 'data' => null] );
        }

        $event = $query->first();
        $expireAt = new Carbon($event->authTokenTime); // 토큰 만료시간

        // 만료 시간 체크
        if (now()->greaterThan($expireAt)) {
            return $this->apiResponse(330, [ 'message' => 'certificationExpired', 'data' => null] );
        }

        return true;
    }
}
