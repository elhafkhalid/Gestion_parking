<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSession extends Model
{
    protected $fillable = [
        'entry_time',
        'exit_time',
        'user_id',
        'place_id',
        'vehicle_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function place(){
        return $this->belongsTo(Place::class);
    }

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
