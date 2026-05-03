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
        'price',
    ];

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'agent_id');
    }
}
