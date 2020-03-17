<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'address2',
        'city',
        'state',
        'zipcode',
    ];
}
