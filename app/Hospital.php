<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name',
        'address',
        'pinCode',
        'email',
        'phone',
        'description',
        'bed',
        'ventilator',
        'oxygen',
        'isolation'
    ];
}
