<?php

namespace m7vm7v\land\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Security extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'pin_allways', 'level', 'passwordfull'];
    
    protected $table = 'security';
    

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function register(User $user) {

        return self::create([
                    'user_id' => $user->id,
        ]);
    }       
    
    public static function updatePasswordfullVeryfcationToTrueIfEnabled($request){
        $user = User::requested($request);
        
        if($user->security()['type'] == 'passwordfull'){
            
            Security::where('user_id', $user->id)->firstOrFail()->update([
                'passwordfull' => true
            ]);
        }
    }
}
