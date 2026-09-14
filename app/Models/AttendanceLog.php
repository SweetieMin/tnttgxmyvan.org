<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $table = 'attendance_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'scanned_token',
        'scanned_name',
    ];

    /**
     * Người đã mở mã QR trên web (tham số ?user= của link QR).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
