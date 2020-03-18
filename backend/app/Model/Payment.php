<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'due_at',
        'amount',
        'payed',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_at' => 'datetime:Y-m-d',
    ];

    protected $dates = [
        'due_at',
        'created_at',
        'updated_at',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
