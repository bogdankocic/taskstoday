<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * Relations to cascade on delete.
     *
     * @var array
     */
    protected $cascadeDeletes = ['notes', 'files', 'votes'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 
        'status', 
        'description', 
        'project_id', 
        'creator_id', 
        'performer_id', 
        'contributor_id',
        'team_id',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the project that task belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the team that task belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the creator that task belongs to.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the performer that task belongs to.
     */
    public function performer()
    {
        return $this->belongsTo(User::class, 'performer_id');
    }

    /**
     * Get the contributor that task belongs to.
     */
    public function contributor()
    {
        return $this->belongsTo(User::class, 'contributor_id');
    }

    /**
     * Get the notes for the task.
     */
    public function notes()
    {
        return $this->hasMany(TaskNote::class);
    }

    /**
     * Get the files for the task.
     */
    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    /**
     * Get the votes for the task.
     */
    public function votes()
    {
        return $this->hasMany(TaskComplexityVote::class);
    }
}
