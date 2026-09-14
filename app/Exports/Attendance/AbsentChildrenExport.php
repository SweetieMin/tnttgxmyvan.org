<?php

namespace App\Exports\Attendance;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class AbsentChildrenExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison, WithColumnFormatting, WithColumnWidths, WithEvents, WithTitle
{
    private const FONT_NAME = 'Times New Roman';
    private const FONT_SIZE = 14;
    private const ROW_HEIGHT = 30;
    private const LAST_COLUMN = 'J';
    private const NOTE_COLUMN = 'I';    // Ghi chú - ai cũng nhập được
    private const ACTION_COLUMN = 'J';  // Xử lý - phải có mật khẩu riêng

    private int $index = 0;

    public function __construct(
        private readonly Collection $children,
        private readonly string $scheduleName = '',
        private readonly string $scheduleDate = '',
        private readonly string $passwordDate = ''
    ) {
    }

    public function collection(): Collection
    {
        return $this->children;
    }

    public function title(): string
    {
        $title = trim($this->scheduleName . ($this->scheduleDate !== '' ? ' ' . $this->scheduleDate : ''));

        // Tên sheet trong Excel tối đa 31 ký tự và không được chứa các ký tự đặc biệt
        $title = str_replace(['\\', '/', '*', '?', ':', '[', ']'], '-', $title);

        return $title !== '' ? mb_substr($title, 0, 31) : 'DS vang';
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tên thánh',
            'Họ và tên',
            'Lớp',
            'Tên cha',
            'Số ĐT cha',
            'Tên mẹ',
            'Số điện thoại mẹ',
            'Ghi chú',
            'Xử lý',
        ];
    }

    public function map($child): array
    {
        return [
            ++$this->index,
            $child->holyName ?? '',
            $child->SimpleName ?? '',
            optional($child->courses->first())->name ?? '',
            optional($child->studentParent)->nameFather ?? '',
            optional($child->studentParent)->phoneFather ?? '',
            optional($child->studentParent)->nameMother ?? '',
            optional($child->studentParent)->phoneMother ?? '',
            '', // Ghi chú - để trống cho người khác nhập
            '', // Xử lý
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7.83,
            'B' => 20,
            'C' => 28.16,
            'D' => 17.5,
            'E' => 42.5,
            'F' => 14,
            'G' => 44.66,
            'H' => 20.5,
            'I' => 91.16,
            'J' => 63.16,
        ];
    }

    /**
     * Mật khẩu khoá sheet: MV + năm tháng ngày của buổi điểm danh (VD: MV20260908).
     */
    public function sheetPassword(): string
    {
        return 'MV' . ($this->passwordDate !== '' ? $this->passwordDate : now()->format('Ymd'));
    }

    /**
     * Mật khẩu riêng cho cột Xử lý. Không cấu hình thì dùng chung mật khẩu khoá sheet.
     */
    private function actionPassword(): string
    {
        $configured = config('attendance.process_password');

        return $configured !== null && $configured !== '' ? (string) $configured : $this->sheetPassword();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->index + 1; // +1 cho dòng tiêu đề
                $all = 'A1:' . self::LAST_COLUMN . $lastRow;

                // Font + canh lề toàn bảng
                $sheet->getStyle($all)->applyFromArray([
                    'font' => ['name' => self::FONT_NAME, 'size' => self::FONT_SIZE],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => ['inside' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // Viền phải của cột cuối (khớp với bản căn chỉnh tay)
                $sheet->getStyle(self::LAST_COLUMN . '1:' . self::LAST_COLUMN . $lastRow)
                    ->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

                // Hai tiêu đề Ghi chú / Xử lý canh giữa
                $sheet->getStyle(self::NOTE_COLUMN . '1:' . self::ACTION_COLUMN . '1')
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Chiều cao dòng
                for ($row = 1; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(self::ROW_HEIGHT);
                }

                // Khoá toàn bộ sheet, chỉ mở cột Ghi chú
                $sheet->getStyle($all)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

                if ($this->index > 0) {
                    $sheet->getStyle(self::NOTE_COLUMN . '2:' . self::NOTE_COLUMN . $lastRow)
                        ->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

                    // Cột Xử lý vẫn khoá, muốn sửa phải nhập mật khẩu riêng
                    $sheet->protectCells(
                        self::ACTION_COLUMN . '2:' . self::ACTION_COLUMN . $lastRow,
                        $this->actionPassword()
                    );
                }

                $protection = $sheet->getProtection();
                $protection->setSheet(true);
                $protection->setPassword($this->sheetPassword());
                $protection->setSort(false);
                $protection->setInsertRows(false);
                $protection->setDeleteRows(false);
                $protection->setFormatCells(false);
            },
        ];
    }
}
