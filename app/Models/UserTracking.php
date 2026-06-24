<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTracking extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'email',
        'url',
        'event_type',
        'element_text'
    ];
}
