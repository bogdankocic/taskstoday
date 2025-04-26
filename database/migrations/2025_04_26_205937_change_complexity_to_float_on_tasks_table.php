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
        Schema::table('tasks', function (Blueprint $table) {
            $table->float('complexity')->nullable()->change();
        });

        Schema::table('task_complexity_vote', function (Blueprint $table) {
            $table->integer('complexity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('complexity')->nullable()->change();
        });

        Schema::table('task_complexity_vote', function (Blueprint $table) {
            $table->dropColumn('complexity');
        });
    }
};
