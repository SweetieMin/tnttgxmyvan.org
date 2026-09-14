<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class AttendanceSchedule extends Model
{
    protected $table = 'attendance_schedules';

    protected $fillable = [
        'name',
        'type',
        'date',
        'start_time',
        'end_time',
        'regulation_id',
        'created_by',
        'status',
    ];

    protected $casts = [
        'applies_to' => 'array',
        'date' => 'date:Y-m-d',
        'start_time' => 'string',
        'end_time' => 'string',
    ];


    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusTextAttribute()
    {
        $now = now();
        $date = $this->date instanceof \Carbon\Carbon ? $this->date->format('Y-m-d') : $this->date;

        $start = \Carbon\Carbon::parse($date . ' ' . $this->start_time);
        $end   = \Carbon\Carbon::parse($date . ' ' . $this->end_time);

        if ($now->lt($start)) {
            $status = 'pending';
        } elseif ($now->between($start, $end)) {
            $status = 'open';
        } else {
            $status = 'closed'; 
        }

        if ($this->status !== $status) {
            $this->status = $status;
            $this->save();
        }

        return $status;
    }
}
