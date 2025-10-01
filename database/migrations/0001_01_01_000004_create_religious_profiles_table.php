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
        Schema::create('religious_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('ngay_rua_toi')->nullable();
            $table->string('linh_muc_rua_toi')->nullable();
            $table->date('ngay_xung_toi')->nullable();
            $table->date('ngay_them_suc')->nullable();
            $table->string('giam_muc_them_suc')->nullable();
            $table->date('ngay_bao_dong')->nullable();
            $table->enum('trang_thai_ton_giao', ['Đã tốt nghiệp', 'Đang học', 'Đã nghỉ'])->default('Đang học');
            $table->date('ngay_bo_hoc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('religious_profiles');
    }
};
