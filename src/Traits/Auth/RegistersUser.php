<?php

namespace m7vm7v\land\Traits\Auth;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use m7vm7v\land\Models\UserFingerprint;
use mv7m7v\land\Models\Security;

trait RegistersUser {

    use \Illuminate\Foundation\Auth\RegistersUsers;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'pin' => 'required|numeric|min:4',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
                
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'pin' => bcrypt($data['pin']),
            'active' => true,
        ]);
        
        UserFingerprint::takeFingerprint($user);
        
        Security::register($user);
        
        Cache::forever('all-users', User::all());
        
        Cache::forever('all-users-fingerprint', UserFingerprint::all());
        
        return $user;
    }
    
        /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(){
        
        activity()
                ->useLog('Register')
                ->causedBy(auth()->user()) //user 
                ->withProperties(['withIP' => request()->ip(), 'level' => '2']) //properties
                ->log('Registered user'); //description
    }
}
