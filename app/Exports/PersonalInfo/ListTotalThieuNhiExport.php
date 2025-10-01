<?php

namespace App\Exports\PersonalInfo;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ListTotalThieuNhiExport implements WithMultipleSheets
{
    protected ?array $courseIds;

    public function __construct(?array $courseIds = null)
    {
        $this->courseIds = $courseIds;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Lấy query lớp theo ordering
        $query = Course::orderBy('ordering');

        // Nếu có truyền danh sách lớp thì lọc
        if (!empty($this->courseIds)) {
            $query->whereIn('id', $this->courseIds);
        }

        $courses = $query->get();

        foreach ($courses as $course) {
            $sheets[] = new ThieuNhiByCourseSheetExport($course);
        }

        return $sheets;
    }
}
