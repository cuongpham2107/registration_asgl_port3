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
        Schema::create('registration_directlies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_card')->nullable();
            $table->string('license_plate')->nullable();
            $table->foreignId('id_load_capacity')->nullable()->constrained('load_capacities')->onDelete('set null');
             $table->foreignId('id_gateway')->nullable()->constrained('gateways')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->dateTime('expected_arrival_time')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            //Chờ vào, Đang vào, Đã ra
            $table->enum('status', ['waiting_entry', 'entering', 'exited'])->default('waiting_entry');
            // 1-1 relationship: each registration_directlie belongs to one registration_vehicle
            $table->foreignId('id_registration_vehicle')->nullable()->unique()->constrained('registration_vehicles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_directlies');
    }
};
