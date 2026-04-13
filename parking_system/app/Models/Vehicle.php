<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'plate_number',
        'type',
        'color',
    ];

    public function parkingRecords()
    {
        return $this->hasMany(ParkingRecord::class);
    }
}
