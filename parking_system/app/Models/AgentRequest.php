<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'age',
        'experience',
        'availability',
        'motivation',
        'identity_document',
        'cv_document',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
