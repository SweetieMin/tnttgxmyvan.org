<?php

namespace App\Models;

use App\Services\ScoreService;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    protected $fillable = [
        'description',
        'type',
        'points',
        'applicable_object',
        'is_active',
        'ordering',
    ];

    protected $casts = [
        'applicable_object' => 'array',
        'is_active' => 'boolean',
        'is_attendance' => 'boolean',
        'ordering' => 'integer',
        'points' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn () => ScoreService::clearDashboardCache());
        static::deleted(fn () => ScoreService::clearDashboardCache());
    }
}
