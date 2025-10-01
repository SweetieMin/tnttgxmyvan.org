<?php

namespace App\Models;

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

}
