<?php

namespace m7vm7v\land\Traits\User;

use App\User;

trait UserHelpers {

    public static function isActive() {
        
        return auth()->user()->active;
    }

    public function makeActive() {

        return User::update([
                    'active' => 1
        ]);
    }

    public function deactive() {

        return User::update([
                    'active' => 0
        ]);
    }

    public function deactivate() {

        return User::update([
                    'active' => 0
        ]);
    }    

    public function getIsAdminAttribute() {
        return true;
    }
    
    public static function requested($request) {
        
        try {
            $user = User::where('email', $request->input('email'))->firstOrFail();
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {

            throw new NoFoundUser;
        }
        
        return $user;
    }
}
