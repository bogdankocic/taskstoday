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
            $table->string('teamrole')->nullable()->after('last_name');
        });

        Schema::table('team_member', function (Blueprint $table) {
            $table->dropColumn('teamrole');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('teamrole');
        });

        Schema::table('team_member', function (Blueprint $table) {
            $table->string('teamrole');
        });
    }
};
