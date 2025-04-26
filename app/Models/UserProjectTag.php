<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserProjectTag extends Pivot
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'user_project_tag';

    /**
     * Get the tag that user project tag belongs to.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the user that user project tag belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that user project tag belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
