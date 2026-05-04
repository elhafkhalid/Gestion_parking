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
        'confirmed_at',
        'canceled_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
