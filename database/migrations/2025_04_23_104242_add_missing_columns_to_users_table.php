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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'email_verified_at']);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('profile_photo')->nullable();
            $table->integer('tasks_completed_count');
            $table->integer('login_after_hours_count');
            $table->integer('login_strike');
            $table->float('karma');
            $table->boolean('is_verified');
            $table->timestamp('last_login_at')->nullable();
            $table->string('password')->nullable()->change();
            $table->softDeletes();

            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'profile_photo', 'tasks_completed_count', 'login_after_hours_count', 'login_strike', 'karma', 'is_verified', 'last_login_at', 'deleted_at']);
            $table->string('name');
            $table->timestamp('email_verified_at');
            $table->string('password')->nullable()->change(false);

            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
