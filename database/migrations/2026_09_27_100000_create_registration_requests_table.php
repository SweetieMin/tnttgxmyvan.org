<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Đơn phụ huynh gửi từ trang public: đăng ký thiếu nhi mới / xin cấp lại thẻ.
     * Chỉ ghi vào users sau khi admin duyệt.
     */
    public function up(): void
    {
        Schema::create('registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Mã hồ sơ cho phụ huynh tra cứu
            $table->enum('type', ['new', 'reissue']);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // cấp lại: em đã có; mới: em được tạo khi duyệt
            $table->foreignId('duplicate_user_id')->nullable()->constrained('users')->nullOnDelete(); // nghi trùng khi đăng ký mới

            // Thông tin thiếu nhi (đăng ký mới)
            $table->string('holyName', 100)->nullable();
            $table->string('fullName')->nullable();
            $table->date('birthday')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();

            // Phụ huynh
            $table->string('nameFather')->nullable();
            $table->string('phoneFather')->nullable();
            $table->string('nameMother')->nullable();
            $table->string('phoneMother')->nullable();
            $table->string('godParent')->nullable();

            // Cấp lại thẻ
            $table->enum('reason', ['lost', 'damaged', 'photo'])->nullable();

            $table->string('contact_phone', 20); // SĐT dùng để tra cứu đơn
            $table->string('picture')->nullable(); // images/registrations/
            $table->text('note')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['status', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_requests');
    }
};
