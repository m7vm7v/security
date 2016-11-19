#Description

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