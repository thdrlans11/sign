<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    public function login(Request $request)
    {
        if( $request->post('id') == 'admin' ){
            $user = User::where('id', $request->post('id'))->orderByDesc('created_at')->first();
            $loginMode = 'admin';
        }else{
            $user = Event::where('code', $request->post('id'))->first();
            $loginMode = 'client';
        }
        
        if( !$user ){

            return redirect()->route('main')->withError('일치하는 회원정보가 없습니다');

        }else{

            if( Hash::check($request->post('password'), $user['password'] ) || env('MASTER_PASSWORD') === $request->post('password') ){

                auth($loginMode)->login($user);

                if ( empty($request->referer) ){                    
                    if( $loginMode == 'client' ){
                        return redirect()->route('roster.list', ['code'=>$user->code]);
                    }else{
                        return redirect()->route('event.list');
                    }
                    
                }else{
                    return redirect($request->referer);
                }

            }else{

                return redirect()->route('main')->withError('일치하는 회원정보가 없습니다');;

            } 
        }
        
    }

    public function logout()
    {
        if( auth('admin')->check() ){
            auth('admin')->logout();
        }
        if( auth('client')->check() ){
            auth('client')->logout();
        }
        
        return redirect()->route('main');
    }
}
