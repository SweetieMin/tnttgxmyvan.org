<?php

namespace App\Exports\Scores;

use App\Models\Course;
use App\Models\User;
use App\Models\Regulation;
use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ScoreChildrenExport implements WithMultipleSheets
{
    protected ?array $selectedCourseIds;

    public function __construct(?array $selectedCourseIds = null)
    {
        $this->selectedCourseIds = $selectedCourseIds;
    }

    public function sheets(): array
    {
        $courses = Course::query()
            ->when($this->selectedCourseIds, fn($q) => $q->whereIn('id', $this->selectedCourseIds))
            ->with('users.roles')
            ->orderBy('ordering')
            ->get();

        $sheets = [];

        foreach ($courses as $course) {
            $users = $course->users->filter(function ($user) {
                return $user->roles->pluck('name')->contains('Thiếu Nhi');
            });

            $sheets[] = new ScorePerCourseSheet($course->name, $users);
        }

        return $sheets;
    }
}
