<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'status', 'description', 'project_id', 'creator_id', 'performer_id', 'contributor_id'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performer_id');
    }

    public function contributor()
    {
        return $this->belongsTo(User::class, 'contributor_id');
    }

    public function notes()
    {
        return $this->hasMany(TaskNote::class);
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }
}
