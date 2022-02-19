<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'userId',
        'hospitalId',
        'bookingType',
        'quantity',
        'bookingDate'
    ];
}
