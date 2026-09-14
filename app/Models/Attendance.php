<?php

namespace App\Models;

use App\Services\ScoreService;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'name',
        'regulation_id',
        'user_id',
        'sector_name',
        'note',
        'submit_by',
        'status',
        'isConfirm'
    ];

    protected $casts = [
        'isConfirm' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(fn () => ScoreService::clearDashboardCache());
        static::deleted(fn () => ScoreService::clearDashboardCache());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submit_by');
    }
}
