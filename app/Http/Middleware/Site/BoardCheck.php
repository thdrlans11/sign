<?php

namespace App\Http\Middleware\Site;

use Closure;
use Illuminate\Support\Facades\Auth;


class BoardCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        
        //게시판 코드
        $code = $request->route()->parameter('code'); 

        //게시판 액션
        $call_method = $request->route()->getActionMethod();
        
        //설정된 게시판인지 체크
        if( !isset(config('site.board')[$code]) ){
            return redirect()->back()->with('alert', '설정된 게시판이 없습니다. CODE 00');
        }

        //회원만 접근 가능한 게시판인지 체크
        if( config('site.board')[$code]['LoginCheck'] && !Auth::check() ){
            return redirect()->back()->with('alert', '접근 권한이 없습니다. CODE 01');
        }
        
        $userLevel = auth('web')->check() ? auth('web')->user()->user_level : 'N';

        //각 액션 권한 체크, 관리자는 프리패스
        if( !isAdminCheck() ){
            
            //리스트 권한
            if( ( $call_method == 'list' || $call_method == 'calendar' ) && !in_array( $userLevel , config('site.board')[$code]['PermitList'] ) ){
                return redirect()->back()->with('alert', '접근 권한이 없습니다. CODE 02');        
            }

            //뷰 권한
            if( $call_method == 'view' && !in_array( $userLevel , config('site.board')[$code]['PermitView'] ) ){
                return redirect()->back()->with('alert', '접근 권한이 없습니다. CODE 03');        
            }

            //입력 권한
            if( $call_method == 'form' && !in_array( $userLevel , config('site.board')[$code]['PermitPost'] ) ){
                return redirect()->back()->with('alert', '접근 권한이 없습니다. CODE 04');        
            }

            //답글 권한
            if( $call_method == 'reply' && !in_array( $userLevel , config('site.board')[$code]['PermitReply'] ) ){
                return redirect()->back()->with('alert', '접근 권한이 없습니다. CODE 05');        
            }
            
            //삭제,수정 권한
            if( $call_method == 'delete' ){
                if( $request->route()->parameter('board')->id != Auth::user()->user_id ){
                    return redirect()->back()->with('alert', '접근 권한이 없습니다. CODE 06');        
                }
            }

        }

        return $next($request);
    }
}
