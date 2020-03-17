<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'team';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'own_equipment',
    ];
}
