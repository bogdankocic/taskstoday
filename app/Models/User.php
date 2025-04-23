<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The achievements that belong to the user.
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievement');
    }

    /**
     * The tags that belong to the user.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'user_project_tag');
    }

    /**
     * The projects that belong to the user.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project_tag');
    }

    /**
     * The teams that belong to the user.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_member')->using(TeamMember::class)->withPivot(['teamrole', 'joined_at']);
    }
}
