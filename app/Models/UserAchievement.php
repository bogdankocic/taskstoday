<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserAchievement extends Pivot
{
    protected $table = 'user_achievement';

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
