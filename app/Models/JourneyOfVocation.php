<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourneyOfVocation extends Model
{
    protected $table = 'journeys_of_vocation';
    protected $fillable = [
        'user_id',
        'ngay_doi_truong',
        'ngay_du_truong',
        'ngay_huynh_truong',
        'ngay_huynh_truong2',
        'ngay_huynh_truong3',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
