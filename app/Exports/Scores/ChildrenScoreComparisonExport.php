<?php

namespace App\Exports\Scores;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ChildrenScoreComparisonExport implements WithMultipleSheets
{
    public function __construct(private readonly Collection $children)
    {
    }

    public function sheets(): array
    {
        $scoreRows = ChildrenScoreComparisonData::for($this->children);

        return [
            new ChildrenScoreComparisonSummarySheet($this->children, $scoreRows),
            new ChildrenScoreComparisonDetailSheet($this->children, $scoreRows),
            new ChildrenScoreComparisonHistorySheet($this->children),
        ];
    }
}
