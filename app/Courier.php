<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    public function user(){
    	return $this->belongsTo(user::class);
    }
}
