<?php

namespace App\Exports\Scores;

use App\Models\Course;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ScoreChildrenExport implements WithMultipleSheets
{
    protected ?array $selectedCourseIds;
    protected ?Collection $children;

    public function __construct(?array $selectedCourseIds = null, ?Collection $children = null)
    {
        $this->selectedCourseIds = $selectedCourseIds;
        $this->children = $children?->unique('id')->values();
    }

    public function sheets(): array
    {
        $selectedUserIds = $this->children?->pluck('id');

        $courses = Course::query()
            ->when($this->selectedCourseIds, fn ($q) => $q->whereIn('id', $this->selectedCourseIds))
            ->when($selectedUserIds, fn ($q) => $q->whereHas(
                'users',
                fn ($userQuery) => $userQuery->whereIn('users.id', $selectedUserIds)
            ))
            ->with('users.roles')
            ->orderBy('ordering')
            ->get();

        $sheets = [];

        foreach ($courses as $course) {
            $users = $course->users
                ->filter(function ($user) {
                    return $user->roles->pluck('name')->contains(fn ($roleName) => str_contains($roleName, 'Nhi'))
                        && (!$this->children || $this->children->contains('id', $user->id));
                })
                ->values();

            $sheets[] = new ScorePerCourseSheet($course->name, $users);
        }

        if (empty($sheets)) {
            $sheets[] = new ScorePerCourseSheet('Khong co du lieu', collect());
        }

        return $sheets;
    }
}
