<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'karma',
        'tasks_completed_count',
        'login_strike',
        'login_after_hours_count',
        'is_verified',
        'role_id',
        'teamrole',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should have default values.
     */
    protected $attributes = [
        'karma' => 0,
        'tasks_completed_count' => 0,
        'login_strike' => 0,
        'login_after_hours_count' => 0,
        'is_verified' => false,
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

    /**
     * The role that belong to the user.
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
