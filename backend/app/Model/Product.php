<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'value',
        'cost',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'cost' => 'decimal:2',
    ];
}
