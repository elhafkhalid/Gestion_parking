<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'number',
        'type',
        'color',
    ];

    public function parkingSessions(){
        return $this->hasMany(ParkingSession::class);
    }
}
