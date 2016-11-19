<?php

namespace mv7m7v\land\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Cache;

class UserFingerprint extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip', 'user_id','city', 'country', 'device', 'browser', 'lang' , 'platform' , 'agent'
    ];    
    
    public static function takeFingerprint(User $user) {
        
        $agent = new Agent();
        
        return self::create([
            'ip' => request()->ip(),
            'user_id' => $user->id,
            'city' => geoip()->getLocation(request()->ip())->city,
            'country' => geoip()->getLocation(request()->ip())->country,
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'lang' => $agent->languages()[0],
            'platform' => $agent->platform(),
            'agent' => request()->header()['user-agent'][0],
        ]);
    }
    
    /**
     * Fingerprint belongs to a User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        
        return $this->belongsTo('App\User');
    }
    
    public static function getCachedUserFingerprint() {
        
        return current(current(Cache::get('all-users-fingerprint')->where('user_id', auth()->user()->id)));
    }
    
    public static function isThereAnyChanges() {
        
        $agent = new Agent();
        
        if(     
                self::getCachedUserFingerprint()->ip != request()->ip() ||
                self::getCachedUserFingerprint()->city != geoip()->getLocation(request()->ip())->city ||
                self::getCachedUserFingerprint()->country != geoip()->getLocation(request()->ip())->country ||
                self::getCachedUserFingerprint()->device != $agent->device() ||
                self::getCachedUserFingerprint()->browser != $agent->browser() ||
                self::getCachedUserFingerprint()->lang != $agent->languages()[0] ||
                self::getCachedUserFingerprint()->platform != $agent->platform() ||
                self::getCachedUserFingerprint()->agent != request()->header()['user-agent'][0] 
        ){
            return true;
        }
        
        return false;
    }   
    
    public static function updateFingerprint() {
        
        $agent = new Agent();
        
        $fingerprint = self::where('user_id', auth()->user()->id)->update([
            'ip' => request()->ip(),
            'user_id' => auth()->user()->id,
            'city' => geoip()->getLocation(request()->ip())->city,
            'country' => geoip()->getLocation(request()->ip())->country,
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'lang' => $agent->languages()[0],
            'platform' => $agent->platform(),
            'agent' => request()->header()['user-agent'][0],
        ]);
        
        Cache::forever('all-users-fingerprint', UserFingerprint::all());
        
        return $fingerprint;
    }
}
