<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    //예외상황이 발생되면 로그를 남기고 report->render 순으로 작동
    public function report(Throwable $e)
    {
        if( $e->getMessage() ){
            Log::error($e->getMessage());
            return parent::report($e);
        }
        
    }

    //예외상황이 발생되면 특정 예외상황에 커스텀으로 처리할수있음.
    public function render($request, Throwable $e)
    {
        if ($e->getMessage() == 'CSRF token mismatch.') { // csrf 토큰 만료
            $url = env('APP_URL');

            switch (CheckUrl()) {
                case 'admin':
                    $url = config('site.common.admin.url');
                    break;

                case 'web':
                    $url = config('site.common.web.url');
                    break;
            }

            return redirect($url)->with('alert', '비정상적으로 접근하셨거나, 로그인 세션이 만료되었습니다.');
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return redirect()->route('main');
        }

        return parent::render($request, $e);
    }
}
