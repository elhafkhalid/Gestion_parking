<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'plate_number',
        'marque'
    ];

    public function parkingRecords()
    {
        return $this->hasMany(ParkingRecord::class);
    }
}
