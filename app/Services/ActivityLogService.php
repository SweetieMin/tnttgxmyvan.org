<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public static function log($action, $objectType, $objectId = null, $description = null)
    {
        $user = User::find(Auth::id());

        if (!$user || $user->roles()->where('name', 'admin')->exists()) {
            return;
        }

        $existingLog = ActivityLog::where('user_id', $user->id)
            ->where('action', $action)
            ->where('object_type', $objectType)
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->first();

        $newNames = explode(', ', $description);

        if ($existingLog) {
            $oldDescription = $existingLog->description;
            $split = explode(':', $oldDescription, 2);
            $existingNames = isset($split[1]) ? explode(', ', trim($split[1])) : [];

            $allNames = collect(array_merge($existingNames, $newNames))->unique()->values()->implode(', ');

            $existingLog->update([
                'object_id'   => null,
                'description' => "$objectType: $allNames", // ✅ gom theo objectType
            ]);
        } else {
            ActivityLog::create([
                'user_id'     => $user->id,
                'action'      => $action,
                'object_type' => $objectType,
                'object_id'   => $objectId,
                'description' => "$objectType: $description", // ✅ theo yêu cầu
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        }
    }
}
