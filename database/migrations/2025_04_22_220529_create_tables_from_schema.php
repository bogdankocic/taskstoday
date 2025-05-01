<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('profile_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('favicon');
            $table->json('condition')->nullable();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('color');
            $table->string('border_color')->nullable();
            $table->string('favicon');
        });
        
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_seen');
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('performer_id')->nullable()->constrained('users');
            $table->foreignId('contributor_id')->nullable()->constrained('users');
        });

        Schema::create('team_member', function (Blueprint $table) {
            $table->id();
            $table->string('teamrole');
            $table->timestamp('joined_at');

            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });

        Schema::create('task_notes', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });
        
        Schema::create('user_achievement', function (Blueprint $table) {
            $table->id();

            $table->foreignId('achievement_id')->constrained('achievements')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });

        Schema::create('user_project_tag', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
        });

        Schema::create('task_file', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
        });

        Schema::create('project_file', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('team_member');
        Schema::dropIfExists('task_notes');
        Schema::dropIfExists('user_achievement');
        Schema::dropIfExists('user_project_tag');
        Schema::dropIfExists('task_file');
        Schema::dropIfExists('project_file');
    }
};
