<?php

namespace App\Exports\Scores;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ChildrenScoreComparisonDetailSheet implements FromArray, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly Collection $children,
        private readonly Collection $scoreRows
    ) {
    }

    public function title(): string
    {
        return 'Chi tiet';
    }

    public function array(): array
    {
        $children = $this->children->values();
        $firstChild = $children[0];
        $secondChild = $children[1];

        $rows = [
            [
                'Nội quy',
                'Loại',
                'Điểm/lần',
                'Số lần - ' . $firstChild->SimpleName,
                'Điểm - ' . $firstChild->SimpleName,
                'Số lần - ' . $secondChild->SimpleName,
                'Điểm - ' . $secondChild->SimpleName,
                'Chênh lệch',
            ],
        ];

        foreach ($this->scoreRows as $scoreRow) {
            $regulation = $scoreRow['regulation'];
            $firstScore = $scoreRow['children'][$firstChild->id] ?? ['count' => 0, 'total' => 0];
            $secondScore = $scoreRow['children'][$secondChild->id] ?? ['count' => 0, 'total' => 0];

            $rows[] = [
                $regulation->description,
                $regulation->type === 'plus' ? 'Thưởng' : 'Phạt',
                $regulation->points,
                $firstScore['count'],
                $firstScore['total'],
                $secondScore['count'],
                $secondScore['total'],
                $firstScore['total'] - $secondScore['total'],
            ];
        }

        return $rows;
    }
}
