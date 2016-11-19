<?php

namespace m7vm7v\land\Middleware;

use Closure;
use m7vm7v\land\Models\UserFingerprint;

class CheckUserFingerprint
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
        
        if((auth()->user() && auth()->user()->isActive()) && UserFingerprint::isThereAnyChanges()){            
            
            auth()->user()->deactivate();
        }
        
        return $next($request);
    }
}
