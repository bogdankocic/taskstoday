<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'user_achievement';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get the achievement that user achievement belongs to.
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * Get the user that user achievement belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
