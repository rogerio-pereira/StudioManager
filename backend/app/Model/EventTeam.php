<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventTeam extends Model
{
    protected $table = 'event_team';
    
    protected $fillable = [
        'event_id',
        'member_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
