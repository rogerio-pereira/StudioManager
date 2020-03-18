<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'customer_id',
        'date',
        'place',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function team()
    {
        return $this->belongsToMany(Team::class, 'event_team', 'event_id', 'member_id');
    }
}
