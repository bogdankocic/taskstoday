<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComplexityVote extends Model
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'task_complexity_vote';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id', 
        'user_id'
    ];

    /**
     * Get the team that member belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user that member belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
