<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectChatMessage extends Model
{
    /**
     * Indicates database table for the model.
     */
    protected $table = 'chat_message';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'text',
        'user_id',
        'project_id',
    ];

    /**
     * Get the user who sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the message.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
