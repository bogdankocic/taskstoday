<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, Notifiable, CascadeSoftDeletes;

    /**
     * Relations to cascade on delete.
     *
     * @var array
     */
    protected $cascadeDeletes = ['notes'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'karma',
        'tasks_completed_count',
        'tasks_completed_strike',
        'login_strike',
        'login_after_hours_count',
        'last_login_at',
        'is_verified',
        'role_id',
        'organization_id',
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
        'tasks_completed_strike' => 0,
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
     * Get the organization that user belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
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
        return $this->belongsToMany(Tag::class, 'user_project_tag')->withPivot(['project_id']);
    }

    /**
     * The projects that belong to the user through teams.
     */
    public function allProjects()
    {
        return $this->teams()->with('project')->get()->pluck('project')->flatten();
    }

    /**
     * The teams that belong to the user.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_member')->using(TeamMember::class)->withPivot(['joined_at']);
    }

    /**
     * The role that belong to the user.
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * The tasks that belong to the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'performer_id', 'id');
    }

    /**
     * The tasks that user created.
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'creator_id', 'id');
    }

    /**
     * The notes that user created.
     */
    public function notes()
    {
        return $this->hasMany(TaskNote::class);
    }
}
