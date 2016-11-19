<?php

namespace m7vm7v\land\Middleware;

use Closure;

class LoginWithPasswordAfterEmail {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next) {
        
        if(auth()->user() && auth()->user()->needsToProvidePassword()){
            
            if($request->path() == 'password' || $request->path() == 'logout'){
                return $next($request);
            }
            
            return redirect('/password')->withErrors('You have to provide your password now.');
        }
        
        return $next($request);
    }
    
    

}
