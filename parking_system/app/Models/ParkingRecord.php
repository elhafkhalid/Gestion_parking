<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingRecord extends Model
{
    protected $fillable = [
        'vehicle_id',
        'place_id',
        'agent_id',
        'entry_time',
        'exit_time',
        'total_price',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
