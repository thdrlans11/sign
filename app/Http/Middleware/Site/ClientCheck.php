<?php

namespace App\Http\Middleware\Site;

use Closure;
use Illuminate\Http\Request;

class ClientCheck
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
        if( !auth('client')->check() && !auth('admin')->check() ){
            session()->flash('previous_url', $request->fullUrl());
            return redirect('/?referer=true');
        }

        return $next($request);
    }
}
