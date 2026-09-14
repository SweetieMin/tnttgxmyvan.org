<?php

namespace App\Exports\Scores;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChildrenScoreComparisonHistorySheet implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    private ?array $rows = null;
    private array $differentRows = [];

    public function __construct(private readonly Collection $children)
    {
    }

    public function title(): string
    {
        return 'Lich su ghi nhan';
    }

    public function array(): array
    {
        return $this->buildRows();
    }

    public function styles(Worksheet $sheet): array
    {
        $this->buildRows();

        $sheet->getStyle('A1:B1')->getFont()->setBold(true);

        foreach ($this->differentRows as $rowNumber) {
            $sheet->getStyle("A{$rowNumber}:B{$rowNumber}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFFF0000');
        }

        return [];
    }

    private function buildRows(): array
    {
        if ($this->rows !== null) {
            return $this->rows;
        }

        $children = $this->children->values();
        $firstChild = $children[0];
        $secondChild = $children[1];

        $rows = [
            [$firstChild->SimpleName, $secondChild->SimpleName],
        ];

        $alignedRows = $this->alignHistories(
            $this->attendanceNamesFor($firstChild->id),
            $this->attendanceNamesFor($secondChild->id)
        );

        foreach ($alignedRows as $alignedRow) {
            $rows[] = [$alignedRow['first'], $alignedRow['second']];

            if ($alignedRow['different']) {
                $this->differentRows[] = count($rows);
            }
        }

        return $this->rows = $rows;
    }

    private function attendanceNamesFor(int $childId): array
    {
        return Attendance::query()
            ->with('regulation')
            ->where('user_id', $childId)
            ->where('isConfirm', true)
            ->where('status', 1)
            ->orderBy('attendances.created_at')
            ->orderBy('attendances.id')
            ->get()
            ->map(fn (Attendance $attendance) => $this->formatAttendanceName($attendance))
            ->filter()
            ->values()
            ->all();
    }

    private function formatAttendanceName(Attendance $attendance): string
    {
        $name = trim((string) $attendance->name);

        if (!$attendance->regulation) {
            return $name;
        }

        $points = (int) $attendance->regulation->points;

        if ($points === 0) {
            return $name;
        }

        $prefix = $attendance->regulation->type === 'plus' ? '+' : '-';

        return $name . " ({$prefix}{$points} điểm)";
    }

    private function alignHistories(array $firstHistory, array $secondHistory): array
    {
        $firstCount = count($firstHistory);
        $secondCount = count($secondHistory);
        $dp = array_fill(0, $firstCount + 1, array_fill(0, $secondCount + 1, 0));

        for ($i = $firstCount - 1; $i >= 0; $i--) {
            for ($j = $secondCount - 1; $j >= 0; $j--) {
                if ($firstHistory[$i] === $secondHistory[$j]) {
                    $dp[$i][$j] = $dp[$i + 1][$j + 1] + 1;
                } else {
                    $dp[$i][$j] = max($dp[$i + 1][$j], $dp[$i][$j + 1]);
                }
            }
        }

        $alignedRows = [];
        $i = 0;
        $j = 0;

        while ($i < $firstCount && $j < $secondCount) {
            if ($firstHistory[$i] === $secondHistory[$j]) {
                $alignedRows[] = [
                    'first' => $firstHistory[$i],
                    'second' => $secondHistory[$j],
                    'different' => false,
                ];
                $i++;
                $j++;
                continue;
            }

            if ($dp[$i + 1][$j] >= $dp[$i][$j + 1]) {
                $alignedRows[] = [
                    'first' => $firstHistory[$i],
                    'second' => '',
                    'different' => true,
                ];
                $i++;
            } else {
                $alignedRows[] = [
                    'first' => '',
                    'second' => $secondHistory[$j],
                    'different' => true,
                ];
                $j++;
            }
        }

        while ($i < $firstCount) {
            $alignedRows[] = [
                'first' => $firstHistory[$i],
                'second' => '',
                'different' => true,
            ];
            $i++;
        }

        while ($j < $secondCount) {
            $alignedRows[] = [
                'first' => '',
                'second' => $secondHistory[$j],
                'different' => true,
            ];
            $j++;
        }

        return $alignedRows;
    }
}
