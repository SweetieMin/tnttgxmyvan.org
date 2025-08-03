<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentReligious extends Model
{
    protected $table = 'religious_profiles';

    protected $fillable = [
        'user_id',
        'ngay_rua_toi',
        'linh_muc_rua_toi',
        'ngay_xung_toi',
        'ngay_them_suc',
        'giam_muc_them_suc',
        'ngay_bao_dong',
        'trang_thai_ton_giao',
        'ngay_bo_hoc',
    ];

    protected $casts = [
        'ngay_rua_toi' => 'date',
        'ngay_xung_toi' => 'date',
        'ngay_them_suc' => 'date',
        'ngay_bao_dong' => 'date',
        'ngay_bo_hoc' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
