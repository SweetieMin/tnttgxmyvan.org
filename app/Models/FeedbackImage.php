<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedbackImage extends Model
{
    use HasFactory;
    protected $table = 'feedback_images';

    protected $fillable = [
        'feedback_id',
        'file_name',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

}
