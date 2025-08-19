<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    public function classe(){
        return $this->belongsTo(Classe::class);

    }
}
