<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'project_file';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'project_id',
        'path',
    ];

    /**
     * Get the project that file belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
