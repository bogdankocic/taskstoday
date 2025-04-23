<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'content', 
        'is_seen', 
        'user_id'
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
    * Get the user that the notification belongs to.
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
