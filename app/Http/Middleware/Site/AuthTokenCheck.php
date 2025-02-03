<?php

namespace App\Http\Middleware\Site;

use Closure;
use Illuminate\Http\Request;
use App\Services\Api\AuthService;

class AuthTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $checkResult = (new AuthService())->authTokenCheck($request);

        if ($checkResult !== true) {
            return $checkResult;
        }

        $request->merge(['authToken' => $request->bearerToken()]);

        return $next($request);
    }
}
