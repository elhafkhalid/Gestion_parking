<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $fillable = [
        'name',
        'address',
        'total_places',
        'opening_hours',
        'email',
        'phone',
        'price_car',
        'price_motorcycle',
    ];

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
