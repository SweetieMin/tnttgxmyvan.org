<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    protected $fillable = [
        'user_id',
        'isHideUser',
        'type',
        'title',
        'content',
        'handled_by',
        'status',
        'resolved_at',
        'note',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'isHideUser' => 'boolean',
    ];

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : null;
    }

    public function getFormattedResolvedAtAttribute()
    {
        return $this->resolved_at ? $this->resolved_at->format('d/m/Y H:i') : null;
    }

    public function images()
    {
        return $this->hasMany(FeedbackImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
