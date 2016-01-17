<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    public function admin() {
    	return $this->belongsTo('App\User');
    }

    public function members() {
        return $this->belongsToMany('App\User');
    }
    public function tasks() {
        return $this->hasMany('App\Task');
    }

    public function isAdmin($user_id) {
    	return $this->admin_id == $user_id;
    }

    public function hasMember($user_id) {
        foreach ($this->members as $member) {
            if ($member->id == $user_id) return true;
        }
        return false;
    }
}
