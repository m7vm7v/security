<?php

namespace m7vm7v\land\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\UserFingerprint;

class PinController extends Controller {

    use \App\Http\Traits\ThrottlesPins;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Show the view for Pin.
     *
     * @return instance of View
     */
    public function getView() {

        if (Auth::user()->active == true) {
            return redirect('/home');
        }

        return view('auth.passwords.pin');
    }

    /**
     * Check the pin.
     *
     * @param  Request  $request
     * @return Response
     */
    public function checkThePin(Request $request) {

        $this->validator($request->all())->validate();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyPinAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutPinResponse($request);
        }

        return $this->updateUserWith($request);
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'pin' => 'required|min:4|numeric',
        ]);
    }

    /**
     * Update user if the credential are fine.
     *
     * @param  Request $request
     * @return redirect with errors or to home.
     */
    protected function updateUserWith(Request $request) {
        
        if (Hash::check($request->input('pin'), Auth::user()->pin)) {
            
            // The passwords match...
            auth()->user()->makeActive();
            
            $this->clearPinAttempts($request);
            
            UserFingerprint::updateFingerprint();

            return redirect('/home');
        }

        // If the PIN attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the PIN form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementPinAttempts($request);

        return back()
                        ->withErrors([Auth::user()->username => 'Wrong PIN']);
    }

}
