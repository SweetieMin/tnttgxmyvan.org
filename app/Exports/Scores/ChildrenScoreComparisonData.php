<?php

namespace App\Exports\Scores;

use App\Models\Attendance;
use App\Models\Regulation;
use Illuminate\Support\Collection;

class ChildrenScoreComparisonData
{
    public static function for(Collection $children): Collection
    {
        $children = $children->values();
        $userIds = $children->pluck('id');
        $roleNames = $children
            ->flatMap(fn ($child) => $child->roles->pluck('name'))
            ->unique()
            ->values();

        $regulations = Regulation::query()
            ->where(function ($query) use ($roleNames) {
                foreach ($roleNames as $roleName) {
                    $query->orWhereJsonContains('applicable_object', $roleName);
                }
            })
            ->orderBy('ordering')
            ->orderBy('id')
            ->get();

        $attendanceCounts = Attendance::query()
            ->select('user_id', 'regulation_id')
            ->selectRaw('COUNT(*) as total')
            ->whereIn('user_id', $userIds)
            ->whereIn('regulation_id', $regulations->pluck('id'))
            ->where('isConfirm', true)
            ->where('status', 1)
            ->groupBy('user_id', 'regulation_id')
            ->get()
            ->keyBy(fn ($row) => $row->user_id . ':' . $row->regulation_id);

        return $regulations->map(function (Regulation $regulation) use ($children, $attendanceCounts) {
            $row = [
                'regulation' => $regulation,
                'children' => [],
            ];

            foreach ($children as $child) {
                $roleNames = $child->roles->pluck('name');
                $isApplicable = $roleNames->intersect($regulation->applicable_object ?? [])->isNotEmpty();
                $count = $isApplicable ? (int) optional($attendanceCounts->get($child->id . ':' . $regulation->id))->total : 0;
                $points = $regulation->points * $count;
                $signedPoints = $regulation->type === 'plus' ? $points : -$points;

                $row['children'][$child->id] = [
                    'count' => $count,
                    'reward' => $regulation->type === 'plus' ? $points : 0,
                    'discipline' => $regulation->type === 'plus' ? 0 : $points,
                    'total' => $signedPoints,
                ];
            }

            return $row;
        });
    }
}
