<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'description', 
        'favicon', 
        'condition'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'condition' => 'array'
    ];

    
    /**
     * The users that belong to the achievement.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievement')->using(UserAchievement::class);
    }
}
