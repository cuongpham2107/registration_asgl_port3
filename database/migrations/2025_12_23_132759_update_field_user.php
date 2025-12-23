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
            $table->string('username')->nullable()->after('id');
            $table->string('asgl_id')->nullable()->after('username');
            $table->string('mobile_phone')->nullable()->after('asgl_id');
            $table->string('avatar')->nullable()->after('mobile_phone');
            $table->string('department_name')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('asgl_id');
            $table->dropColumn('mobile_phone');
            $table->dropColumn('avatar');
            $table->dropColumn('department_name');
        });
    }
};
