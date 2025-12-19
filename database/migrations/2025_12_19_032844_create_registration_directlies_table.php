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
            $table->string('load_capacity')->nullable();
            $table->string('entry_gate')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            //Chờ vào, Đang vào, Đã ra
            $table->enum('status', ['waiting_entry', 'entering', 'exited']);
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
