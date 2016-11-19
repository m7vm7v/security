<?php

namespace m7vm7v\land\Traits\User;

use App\User;
use m7vm7v\land\Models\Security;

trait Securable {

    public function security() {

        return current(User::hasOne('App\Security')->get()->toArray());
    }

    public function needsToProvidePassword() {

        return auth()->user()->security()['type'] == 'passwordfull' && auth()->user()->security()['passwordfull'] == true;
    }

    /** Check if the security level is 5 Email verification then Password Verification
     * 
     * @return User
     */
    public function updatePasswordfullVeryfcationToFalse() {

        if (auth()->user() && auth()->user()->security()['type'] == 'passwordfull') {
            
            Security::where('user_id', auth()->user()->id)->firstOrFail()->update([
                'passwordfull' => false
            ]);
        }
    }

    /** Check if the security level is 6 PIN activated
     * 
     * @return User
     */
    public static function checkForPINSecurity() {

        if (auth()->user() && auth()->user()->security()['pin_allways'] == true) {

            return auth()->user()->deactivate();
        }
    }

    /** Check if the security level is 2 or 4 Email verification needed
     * 
     * @return boolean
     */
    public static function checkForEmailOnlySecurity($request) {

        $user = User::requested($request);

        return $user->security()['type'] == 'email' || $user->security()['type'] == 'passwordfull';
    }

    /** Check if the security level is 3 Email verification needed
     * 
     * @return boolean
     */
    public static function checkForPasswordOnlySecurity($request) {

        $user = User::requested($request);

        return $user->security()['type'] == 'password';
    }

}
