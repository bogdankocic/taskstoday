<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'task_id'
    ];

    /**
     * Get the task that file belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
