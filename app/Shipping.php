<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
        protected $fillable = [

        'startpin', 'endpin','price','iscod','codprice'

    ];
}
