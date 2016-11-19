#Description

App\Controllers\Auth\RegisterController.php
delete validator & create, change the following
use \App\Http\Traits\Auth\RegistersUser;

App\Controllers\Auth\LoginController.php
change the following 
use \App\Http\Traits\Auth\AuthenticatesUsers;

App\Http\Kernel.php
protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\CheckPinIfNotActiveUser::class,
            \App\Http\Middleware\CheckUserFingerprint::class,
            \App\Http\Middleware\LoginWithPasswordAfterEmail::class,
            \App\Http\Middleware\LogLastUserActivity::class,
        ],