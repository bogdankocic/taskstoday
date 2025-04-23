<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProjectTag extends Model
{
    protected $table = 'user_project_tag';

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
