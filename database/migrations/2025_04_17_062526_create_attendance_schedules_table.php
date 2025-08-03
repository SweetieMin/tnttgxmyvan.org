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
        Schema::create('attendance_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // đổi 'title' thành 'name' để chuẩn hóa
            $table->foreignId('regulation_id')->nullable()->constrained('regulations')->onDelete('set null');
            $table->date('date');
            $table->time('start_time'); // chuyển từ timestamp -> time
            $table->time('end_time');   // chuyển từ timestamp -> time
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'open', 'closed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_schedule');
    }
};
