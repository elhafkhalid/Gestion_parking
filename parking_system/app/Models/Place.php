<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'parking_id',
        'number',
        'is_occupied',
    ];

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    public function parkingRecords()
    {
        return $this->hasMany(ParkingRecord::class);
    }
}
