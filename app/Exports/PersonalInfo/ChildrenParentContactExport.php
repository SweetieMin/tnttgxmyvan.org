<?php

namespace App\Exports\PersonalInfo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ChildrenParentContactExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison, ShouldAutoSize, WithColumnFormatting
{
    public function __construct(private readonly Collection $children)
    {
    }

    public function collection(): Collection
    {
        return $this->children;
    }

    public function headings(): array
    {
        return [
            'Tên thánh',
            'Họ và tên',
            'Tên cha',
            'Số ĐT cha',
            'Tên mẹ',
            'Số điện thoại mẹ',
        ];
    }

    public function map($child): array
    {
        return [
            $child->holyName ?? '',
            $child->SimpleName ?? '',
            optional($child->studentParent)->nameFather ?? '',
            optional($child->studentParent)->phoneFather ?? '',
            optional($child->studentParent)->nameMother ?? '',
            optional($child->studentParent)->phoneMother ?? '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
