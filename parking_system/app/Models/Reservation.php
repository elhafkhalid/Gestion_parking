<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'place_id',
        'vehicle_id',
        'reservation_date',
        'reservation_time',
        'reserved_at',
        'canceled_at',
    ];

    public function vehicle()
    {
        return $this->belongsTo(vehicle::class);
    }

    public function place()
    {
        return $this->belongsTo(place::class);
    }
}
