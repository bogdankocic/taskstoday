<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['title', 'description', 'favicon', 'condition'];
    protected $casts = ['condition' => 'array'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievement');
    }
}
