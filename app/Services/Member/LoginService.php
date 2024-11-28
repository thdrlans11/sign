<?php

namespace App\Services\Member;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginService
 * @package App\Services
 */
class LoginService
{
    public function login(Request $request)
    {
        $user_id = $request->post('id');
        $user_password = $request->post('password');

        $user = User::where('user_id', $user_id)->where('del','N')->orderByDesc('reg_date')->first();
        
        if( !$user ){

            return redirect()->route('loginShow')->withError('일치하는 회원정보가 없습니다.');

        }else{
            
            if( Hash::check($user_password, $user['password'] ) || env('MASTER_PASSWORD') === $user_password ){
                
                auth('web')->login($user);

                if( $user->isAdmin() ){
                    auth('admin')->login($user);
                }
                
                if ( empty($request->referer) ){                    
                    return redirect()->route('main');
                }else{
                    return redirect($request->referer);
                }

            }else{

                return redirect()->route('loginShow')->withError('일치하는 회원정보가 없습니다.');

            } 
        }
        
    }

    public function logout()
    {
        if (auth('admin')->check() && (auth('admin')->id() == auth('web')->id())) {
            auth('admin')->logout();
        }

        auth('web')->logout();
        return redirect()->route('main');
    }
}
