<?php

namespace m7vm7v\land\Traits\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use m7vm7v\land\Models\EmailLogin;
use Illuminate\Support\Facades\Mail;
use App\User;
use m7vm7v\land\Models\Security;

trait AuthenticatesUsers {

    use \Illuminate\Foundation\Auth\AuthenticatesUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {

        return view('auth.login');
    }

    /**
     * Show the form for using Password to login.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPasswordLoginForm() {
        return view('auth.login_password');
    }

    public function passwordVerification() {

        if ( ! auth()->user() || ! auth()->user()->needsToProvidePassword()) {
            
            return redirect('/home');
        }

        return view('auth.passwords.pass');
    }

    /**
     * Figures out which login mechanism to use.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response|mixed
     */
    public function resolveLogin(Request $request) {
                
        if ($request->has('password')) {

            return $this->loginByPassword($request);
        }

        Security::updatePasswordfullVeryfcationToTrueIfEnabled($request);
        
        return $this->loginWithoutPassword($request);
    }

    public function loginByPassword($request) {

        if (User::checkForEmailOnlySecurity($request)) {

            return redirect('/login')->withErrors('You can login only with your email.');
        }
        return $this->login($request);
    }

    public function loginWithoutPassword($request) {

        if (User::checkForPasswordOnlySecurity($request)) {

            return redirect('/login/password')->withErrors('You can login only with your email and password.');
        }

        return $this->loginWithMagicLink($request);
    }

    /**
     * Handle a login request to the application without password by sending email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginWithMagicLink(Request $request) {

        $this->validateLogin($request);

        $emailLogin = EmailLogin::createForEmail($request->input('email'));

        $url = route('login.with_email', [
            'name' => User::where('email', $request->input('email'))->firstOrFail()->name,
            'token' => $emailLogin->token
        ]);

        Mail::send('auth.emails.login_email', ['url' => $url], function ($m) use ($request) {

            $m->from(env('REGISTER_EMAIL_SENDER'), env('REGISTER_EMAIL_TITLE'));
            $m->to($request->input('email'))->subject(env('REGISTER_EMAIL_SUBJECT'));
        });

        return view('auth.check_your_email');
    }

    public function loginWithPassAfterEmail(Request $request) {

        $this->validate($request, ['password' => 'required']);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($this->guard()->attempt($credentials)) {
            
            auth()->user()->updatePasswordfullVeryfcationToFalse();
            
            $this->activity('Login through Passwordfull verification');
            
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Make login request by given token.
     *
     * @param  token
     * @return redirect
     */
    public function authenticateEmail($name, $token) {
        //$name for security options
        $emailLogin = EmailLogin::validFromToken($token);

        Auth::login($emailLogin->user);
        
        $this->activity('Login through Email verification');
        
        User::checkForPINSecurity();

        return redirect('home');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request) {

        if ($request->has('password')) {

            return $this->validate($request, [
                        'email' => 'required|email|exists:users',
                        'password' => 'required',
            ]);
        }

        $this->validate($request, [
            'email' => 'required|email|exists:users'
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated() {
        
        User::checkForPINSecurity();
        
        $this->activity('Login through Password verification');
    }

    protected function activity($name) {
        
        return activity()
                ->useLog('login')
                ->causedBy(auth()->user()) //user 
                ->withProperties(['withIP' => request()->ip(), 'level' => '1']) //properties
                ->log($name); //description
    }
}
