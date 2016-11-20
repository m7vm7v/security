<?php

namespace m7vm7v\land\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use m7vm7v\land\Exceptions\NoEmailToken;
use App\User;

class EmailLogin extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token', 'name'];

    /**
     * A user has one email verification
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * Create email for login
     *
     * @var email
     * @return self
     */
    public static function createForEmail($email) {

        self::deleteOldTokens();

        return self::updateOrCreate(
                [
                    'name' => User::where('email', $email)->firstOrFail()->name,
                    'email' => $email
                ],
                [
                    'token' => str_random(20)
                ]
        );
    }

    /**
     * Validate the given token
     *
     * @var token
     * @return self
     */
    public static function validFromToken($token) {

        try {
            $tokenResult = self::where('token', $token)
                    ->where('created_at', '>', Carbon::parse(env('PIN_EXPIRE_TIME')))
                    ->latest()
                    ->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {

            throw new NoEmailToken;
        }
        
        return $tokenResult;
    }

    public static function deleteOldTokens() {

        return self::where('created_at', '<', Carbon::parse(env('PIN_EXPIRE_TIME')))
                        ->delete();
    }

}
