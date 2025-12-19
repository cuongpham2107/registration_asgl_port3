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
            $table->string('load_capacity');
            $table->string('entry_gate')->nullable();
            $table->timestamp('expected_arrival_time');
            $table->text('notes')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            // Cần phê duyệt, Đã vào, Đã ra, Đã phê duyệt, Từ chối
            $table->enum('status', ['pending_approval', 'entered', 'exited', 'approved', 'rejected']);
            $table->foreignId('id_registration_directlie')->constrained('registration_directlies')->onDelete('cascade');
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
