<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'project_id'
    ];

    /**
     * Get the project that file belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
