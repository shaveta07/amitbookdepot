<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function setFilenamesAttribute($value)

    {

        $this->attributes['images'] = json_encode($value);

    }
}


