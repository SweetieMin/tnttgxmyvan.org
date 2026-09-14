<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ScoreService
{
    private const CACHE_PREFIX = 'dashboard_scores.';

    public static function clearDashboardCache(): void
    {
        Cache::forget(self::CACHE_PREFIX . 'children');
        Cache::forget(self::CACHE_PREFIX . 'scouters');
    }

    public function childrenRanking(?int $limit = null): array
    {
        return $this->ranking('children', $limit);
    }

    public function scouterRanking(?int $limit = null): array
    {
        return $this->ranking('scouters', $limit);
    }

    private function ranking(string $group, ?int $limit = null): array
    {
        $cached = Cache::rememberForever(self::CACHE_PREFIX . $group, function () use ($group) {
            return [
                'updated_at' => now()->toDateTimeString(),
                'scores' => $this->buildRankingRows($group),
            ];
        });

        $scoreRows = collect($cached['scores']);

        if ($limit !== null) {
            $scoreRows = $scoreRows->take($limit);
        }

        $users = $this->hydrateUsers($scoreRows);

        return [
            'users' => $users,
            'updated_at' => Carbon::parse($cached['updated_at']),
        ];
    }

    private function buildRankingRows(string $group): array
    {
        $users = $this->userQuery($group)
            ->select('users.id', 'users.lastName', 'users.name')
            ->get();

        if ($users->isEmpty()) {
            return [];
        }

        $scores = $this->scoreRows($users->pluck('id'))->pluck('total_score', 'user_id');

        return $users
            ->map(function (User $user) use ($scores) {
                return [
                    'user_id' => $user->id,
                    'total_score' => (int) ($scores[$user->id] ?? 0),
                    'sort_name' => $user->SimpleName,
                ];
            })
            ->sortBy([
                ['total_score', 'desc'],
                ['sort_name', 'asc'],
            ])
            ->values()
            ->map(fn (array $row) => [
                'user_id' => $row['user_id'],
                'total_score' => $row['total_score'],
            ])
            ->all();
    }

    private function scoreRows(Collection $userIds): Collection
    {
        return Attendance::query()
            ->select('attendances.user_id')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN regulations.type = 'plus' THEN regulations.points
                        ELSE -regulations.points
                    END
                ) as total_score
            ")
            ->join('regulations', 'regulations.id', '=', 'attendances.regulation_id')
            ->whereIn('attendances.user_id', $userIds)
            ->where('attendances.isConfirm', true)
            ->where('attendances.status', 1)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('role_user')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->whereColumn('role_user.user_id', 'attendances.user_id')
                    ->whereRaw('JSON_CONTAINS(regulations.applicable_object, JSON_QUOTE(roles.name))');
            })
            ->groupBy('attendances.user_id')
            ->get();
    }

    private function hydrateUsers(Collection $scoreRows): Collection
    {
        $ids = $scoreRows->pluck('user_id');

        if ($ids->isEmpty()) {
            return collect();
        }

        $usersById = User::with('roles')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return $scoreRows
            ->map(function (array $row) use ($usersById) {
                $user = $usersById->get($row['user_id']);

                if (!$user) {
                    return null;
                }

                $user->setTotalScore((int) $row['total_score']);

                return $user;
            })
            ->filter()
            ->values();
    }

    private function userQuery(string $group): Builder
    {
        $query = User::query()->where('is_attendance', 1);

        if ($group === 'children') {
            return $query->whereHas('roles', function ($q) {
                $q->where('name', 'Thiếu Nhi');
            });
        }

        return $query->whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['Thiếu Nhi', 'Admin', 'Cha Tuyên Uy']);
        });
    }
}
