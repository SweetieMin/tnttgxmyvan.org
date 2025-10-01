<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'is_active',
        'is_popup',
        'applicable_roles',
        'applicable_sectors',
        'start_at',
        'end_at',
        'created_by',
    ];

    protected $casts = [
        'applicable_roles' => 'array',
        'applicable_sectors' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function isApplicableToUser($user)
    {
        $userRoleNames = $user->roles->pluck('name')->toArray();
        $userSectorNames = $user->sectors->pluck('name')->toArray();

        $roleMatch = empty($this->applicable_roles) || !empty(array_intersect($this->applicable_roles, $userRoleNames));

        $sectorMatch = empty($this->applicable_sectors) || !empty(array_intersect($this->applicable_sectors, $userSectorNames));

        $now = Carbon::now();
        $timeMatch = (!$this->start_at || $this->start_at <= $now) &&
            (!$this->end_at || $this->end_at >= $now);

        return $this->is_active && $roleMatch && $sectorMatch && $timeMatch;
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
