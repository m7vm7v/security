<?php

namespace m7vm7v\land\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;

trait ThrottlesPins
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyPinAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttlePinKey($request), 5, 1
        );
    }
    
    /**
     * Increment the PIN attempts for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    protected function incrementPinAttempts(Request $request)
    {
        $this->limiter()->hit($this->throttlePinKey($request));
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutPinResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttlePinKey($request)
        );

        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);

        return redirect()->back()
            ->withErrors([Auth::user()->username => $message]);
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function clearPinAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttlePinKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request)
    {
        event(new Lockout($request));
    }
    
    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttlePinKey(Request $request)
    {
        return Str::lower(Auth::user()->username).'|'.$request->ip();
    }

    /**
     * Get the rate limiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }
}
