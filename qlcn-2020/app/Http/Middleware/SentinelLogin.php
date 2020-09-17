<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class SentinelLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check())
            return redirect('signin')->with('error', trans('__title.notifications.must_login'));
        return $next($request);
    }
}
