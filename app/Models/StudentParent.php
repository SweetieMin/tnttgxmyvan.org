<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $table = 'student_parents';

    protected $fillable = [
        'user_id',
        'nameFather',
        'nameMother',
        'phoneFather',
        'phoneMother',
        'godParent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
