<?php

namespace m7vm7v\land\Traits\User;

use Illuminate\Support\Facades\Cache;

trait OnlineUsers {

    public function isOnline() {
        return Cache::has('online-user-' . $this->id);
    }

    public static function getOnlineUsers() {

        foreach(Cache::get('all-users') as $user){
            
            if(Cache::has('online-user-' . $user->name)){
                $online_users[] = $user->name;
            }            
        }
        
        return $online_users;
    }
}
