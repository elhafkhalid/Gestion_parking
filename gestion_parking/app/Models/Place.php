<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'number',
        'status',
        'parking_id',
    ];

    public function parking(){
        return $this->belongsTo(Parking::class);
    }

    public function parkingSessions(){
        return $this->hasMany(ParkingSession::class);
    }

    
}
