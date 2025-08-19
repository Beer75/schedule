<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    //
    public function groups(){
        return $this->hasMany(Group::class);
    }
}
