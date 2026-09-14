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
        Schema::create('journeys_of_vocation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('ngay_doi_truong')->nullable();
            $table->date('ngay_du_truong')->nullable();
            $table->date('ngay_huynh_truong')->nullable();
            $table->date('ngay_huynh_truong2')->nullable();
            $table->date('ngay_huynh_truong3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journeys_of_vocation');
    }
};
