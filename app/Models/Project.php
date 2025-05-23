<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * Relations to cascade on delete.
     *
     * @var array
     */
    protected $cascadeDeletes = ['teams', 'tasks', 'files', 'chatMessages'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'status', 
        'description', 
        'organization_id'
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
     * Get the organization that project belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the teams for the project.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the files for the project.
     */
    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Get the chat messages for the project.
     */
    public function chatMessages()
    {
        return $this->hasMany(ProjectChatMessage::class);
    }
}
