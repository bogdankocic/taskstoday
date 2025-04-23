<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $table = 'user_achievement';

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
