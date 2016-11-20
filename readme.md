#Description

composer require m7vm7v/land:dev-master

App\Controllers\Auth\RegisterController.php
delete validator & create, change the following
use m7vm7v\land\Traits\Auth\RegistersUser;

App\Controllers\Auth\LoginController.php
change the following 
use m7vm7v\land\Auth\AuthenticatesUsers;

App\Http\Kernel.php
protected $middlewareGroups = [
        'web' => [
            \m7vm7v\land\Middleware\CheckPinIfNotActiveUser::class,
            \m7vm7v\land\Middleware\CheckUserFingerprint::class,
            \m7vm7v\land\Middleware\LoginWithPasswordAfterEmail::class,
            \m7vm7v\land\Middleware\LogLastUserActivity::class,
        ],
		
User.php
use m7vm7v\land\Traits\User\UserHelpers;
use m7vm7v\land\Traits\User\OnlineUsers;
use m7vm7v\land\Traits\User\Securable;

Router.php
public function auth()
    {
        // Authentication Routes...
        $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
        $this->get('login/password', 'Auth\LoginController@showPasswordLoginForm');
        $this->get('login/with_email/{name}/{token}', 'Auth\LoginController@authenticateEmail')->name('login.with_email');
        $this->post('login', 'Auth\LoginController@resolveLogin');
        $this->post('logout', 'Auth\LoginController@logout')->name('logout');
        $this->get('password', 'Auth\LoginController@passwordVerification');
        $this->post('password', 'Auth\LoginController@loginWithPassAfterEmail');


        // Registration Routes...
        $this->get('register', 'Auth\RegisterController@showRegistrationForm');
        $this->post('register', 'Auth\RegisterController@register');

        // Password Reset Routes...
        $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
        $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
        $this->post('password/reset', 'Auth\ResetPasswordController@reset');

        // Pin Routes
        $this->get('password/pin', '\m7vm7v\land\Controllers\PinController@getView');
        $this->post('password/pin', '\m7vm7v\land\Controllers\PinController@checkThePin');
    }