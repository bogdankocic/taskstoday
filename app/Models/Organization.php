<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 
        'email', 
        'profile_photo'
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
    * Get the projects that the organization has.
    */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
    * Get the users that the organization has.
    */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
