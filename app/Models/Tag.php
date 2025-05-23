<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
        'color', 
        'border_color', 
        'favicon'
    ];

    /**
     * The users that belong to the tag.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project_tag');
    }


    /**
     * The projects that belong to the tag.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project_tag');
    }
}
