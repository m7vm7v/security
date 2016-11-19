<?php

namespace m7vm7v\land\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPinIfNotActiveUser
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
        if(auth()->user() && auth()->user()->needsToProvidePassword()){
            
            return $next($request);
        }
        
        if(Auth::user() && Auth::user()->active == false){
            
            if($request->path() == 'password/pin' || $request->path() == 'logout'){
                return $next($request);
            }
            return redirect('password/pin');
        }
        
        return $next($request);
    }
}
