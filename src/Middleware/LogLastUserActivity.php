<?php

namespace m7vm7v\land\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LogLastUserActivity {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        
        if (Auth::check()) {
            
            Cache::put('online-user-' . Auth::user()->name, true, Carbon::parse(env('ONLINE_USER_EXPIRE_TIME')));
        }

        return $next($request);
    }

}
