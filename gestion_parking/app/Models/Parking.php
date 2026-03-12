<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $fillable = [
        'name',
        'adress',
        'total_places',
        'available_places',
        'opening_hours',
        'price_for_hour',
    ];

    public function places(){
       return $this->hasMany(Place::class);
    }
}
