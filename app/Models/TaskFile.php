<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    protected $fillable = ['title', 'task_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
