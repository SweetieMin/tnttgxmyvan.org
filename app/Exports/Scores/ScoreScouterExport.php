<?php

namespace App\Exports\Scores;

use App\Models\User;
use App\Models\Regulation;
use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ScoreScouterExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private int $index = 0;

    public function collection()
    {
        return User::with(['roles' => function ($q) {
            $q->orderBy('ordering');
        }])
            ->get()
            ->filter(function ($user) {
                $excludedRoles = ['Admin', 'Thiếu Nhi', 'Cha Tuyên Úy'];
                return !$user->roles->pluck('name')->intersect($excludedRoles)->count();
            })
            ->sortBy(function ($user) {
                return optional($user->roles->first())->ordering ?? 9999;
            })
            ->values();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã ID',
            'Tên Thánh',
            'Họ và Tên',
            'Điểm cộng',
            'Điểm trừ',
            'Tổng điểm',
        ];
    }

    public function map($user): array
    {
        [$reward, $discipline] = $this->calculateScore($user);

        return [
            ++$this->index,
            $user->account_code ?? '',
            $user->holyName ?? '',
            $user->SimpleName ?? '',
            $reward,
            $discipline,
            $reward - $discipline,
        ];
    }

    private function calculateScore($user): array
    {
        $userRoles = $user->roles->pluck('name')->toArray();

        $regulations = Regulation::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('applicable_object', $role);
            }
        })->get();

        $reward = 0;
        $discipline = 0;

        foreach ($regulations as $regulation) {
            $count = Attendance::where('user_id', $user->id)
                ->where('isConfirm', true)
                ->where('status', 1)
                ->where('regulation_id', $regulation->id)
                ->count();

            $points = $regulation->points * $count;

            if ($regulation->type === 'plus') {
                $reward += $points;
            } else {
                $discipline += $points;
            }
        }

        return [$reward, $discipline];
    }
}
