<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['title', 'color', 'border_color', 'favicon'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project_tag');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project_tag');
    }
}
