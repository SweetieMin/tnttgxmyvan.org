<?php

namespace App\Exports\PersonalInfo;

use App\Models\User;
use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ThieuNhiByCourseSheetExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison, ShouldAutoSize, WithTitle
{
    private int $index = 0;
    protected Course $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function collection()
    {
        // Lấy học sinh có role 'Thiếu Nhi' thuộc lớp $this->course
        return User::with(['courses', 'sectors', 'roles', 'studentParent', 'religiousProfile'])
            ->whereHas('roles', fn($q) => $q->where('name', 'Thiếu Nhi'))
            ->whereHas('courses', fn($q) => $q->where('courses.id', $this->course->id))
            ->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã ID',
            'Tên Thánh',
            'Họ và Tên',
            'Ngày sinh',
            'Ngày rửa tội',
            'Lớp Giáo Lý',
            'Ngành Sinh hoạt',
            'Địa chỉ',
            'Tên Thánh - Họ và Tên cha',
            'Tên Thánh - Họ và Tên mẹ',
            'Tên Thánh - Họ và Tên người đỡ đầu',
        ];
    }

    public function map($user): array
    {
        return [
            ++$this->index,
            $user->account_code ?? '',
            $user->holyName ?? '',
            $user->SimpleName ?? '',
            $user->birthday ?? '',
            optional(optional($user->religiousProfile)->ngay_rua_toi)?->format('d/m/Y') ?? '',
            optional($user->courses->first())->name ?? '',
            optional($user->sectors->first())->name ?? '',
            $user->address ?? '',
            optional($user->studentParent)->nameFather ?? '',
            optional($user->studentParent)->nameMother ?? '',
            optional($user->studentParent)->godParent ?? '',
        ];
    }

    // Đặt tên sheet là tên lớp
    public function title(): string
    {
        return $this->course->name;
    }
}
