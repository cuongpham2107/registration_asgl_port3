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
        Schema::create('registration_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name');
            $table->string('driver_id_card');
            $table->string('license_plate');
            $table->foreignId('id_load_capacity')->nullable()->constrained('load_capacities')->onDelete('set null');
            $table->foreignId('id_gateway')->nullable()->constrained('gateways')->onDelete('set null');
            $table->timestamp('expected_arrival_time')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('approval_date')->nullable();
            // Cần phê duyệt, Đã vào, Đã ra, Đã phê duyệt, Từ chối
            $table->enum('status', ['pending_approval', 'entered', 'exited', 'approved', 'rejected'])->default('pending_approval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_vehicles');
    }
};
