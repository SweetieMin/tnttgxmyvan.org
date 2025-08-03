<?php

namespace App\Exports\Scores;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ScorePerCourseSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected string $courseName;
    protected $users;
    protected int $index = 0;

    public function __construct(string $courseName, $users)
    {
        $this->courseName = $courseName;
        $this->users = $users;
    }

    public function title(): string
    {
        return $this->courseName;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã ID',
            'Tên Thánh',
            'Họ và Tên',
            'Điểm thưởng',
            'Điểm phạt',
            'Tổng điểm',
        ];
    }

    public function collection()
    {
        return $this->users;
    }

    public function map($user): array
    {
        [$reward, $discipline] = $this->calculateScoreDetail($user);

        return [
            ++$this->index,
            $user->account_code,
            $user->holyName,
            $user->SimpleName,
            $reward,
            $discipline,
            $reward - $discipline,
        ];
    }

    protected function calculateScoreDetail(User $user): array
    {
        $userRoles = $user->roles->pluck('name')->toArray();

        $regulations = \App\Models\Regulation::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('applicable_object', $role);
            }
        })->get();

        $reward = 0;
        $discipline = 0;

        foreach ($regulations as $regulation) {
            $count = \App\Models\Attendance::where('user_id', $user->id)
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
