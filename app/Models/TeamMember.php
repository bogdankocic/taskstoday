<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMember extends Pivot
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'team_member';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'joined_at', 
        'team_id', 
        'user_id'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * Get the team that member belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user that member belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
