<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    public function plans(){
        return $this->hasMany(Plan::class);
    }

    public function schedules(){
        return $this->hasMany(Schedule::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function booted(){
        static::deleting( function(Employer $employer){
            $employer->schedules()->delete();
            $employer->plans()->delete();
            $employer->user()->delete();


            /**
             * $employer->schedules()->each(function($schedule){
             *                                      $schedule->delete();
             *                                });
             *
             *
             */

        });
    }

}
