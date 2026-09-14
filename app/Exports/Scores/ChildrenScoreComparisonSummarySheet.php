<?php

namespace App\Exports\Scores;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ChildrenScoreComparisonSummarySheet implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly Collection $children,
        private readonly Collection $scoreRows
    ) {
    }

    public function title(): string
    {
        return 'Tong quan';
    }

    public function array(): array
    {
        $children = $this->children->values();
        $totals = $children->mapWithKeys(fn ($child) => [$child->id => $this->totalsFor($child->id)]);
        $difference = $totals[$children[0]->id]['total'] - $totals[$children[1]->id]['total'];

        return [
            ['So sánh điểm thiếu nhi'],
            [],
            ['Thông tin', $children[0]->SimpleName, $children[1]->SimpleName],
            ['Mã tài khoản', $children[0]->account_code, $children[1]->account_code],
            ['Tên thánh', $children[0]->holyName, $children[1]->holyName],
            ['Lớp giáo lý', $children[0]->courses->pluck('name')->implode(', '), $children[1]->courses->pluck('name')->implode(', ')],
            ['Ngành', $children[0]->sectors->pluck('name')->implode(', '), $children[1]->sectors->pluck('name')->implode(', ')],
            [],
            ['Điểm thưởng', $totals[$children[0]->id]['reward'], $totals[$children[1]->id]['reward']],
            ['Điểm phạt', $totals[$children[0]->id]['discipline'], $totals[$children[1]->id]['discipline']],
            ['Tổng điểm', $totals[$children[0]->id]['total'], $totals[$children[1]->id]['total']],
            ['Chênh lệch', $difference, -$difference],
        ];
    }

    private function totalsFor(int $childId): array
    {
        return [
            'reward' => $this->scoreRows->sum(fn ($row) => $row['children'][$childId]['reward'] ?? 0),
            'discipline' => $this->scoreRows->sum(fn ($row) => $row['children'][$childId]['discipline'] ?? 0),
            'total' => $this->scoreRows->sum(fn ($row) => $row['children'][$childId]['total'] ?? 0),
        ];
    }
}
