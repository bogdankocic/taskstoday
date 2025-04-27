<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'task_file';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'task_id',
        'path',
    ];

    /**
     * Get the task that file belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
