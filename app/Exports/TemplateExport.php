<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TemplateExport implements WithTitle, WithDrawings
{
    protected $title;
    protected $heightRowInfo;
    protected $heightColumns;

    public function __construct($title, $heightRowInfo = 17, $heightColumns = 'J')
    {
        $this->title         = $title;
        $this->heightRowInfo = $heightRowInfo;
        $this->heightColumns = $heightColumns;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is the company logo.');
        $drawing->setPath(public_path('images/logo-s-v2.png')); // Thay đường dẫn ảnh của bạn
        $drawing->setHeight(50);
        $drawing->setCoordinates('A4');

        return $drawing;
    }

    public function registerEventsTemplate($sheet): void
    {
        $this->mergeCell($sheet);
        $this->setCellValue($sheet);
        $this->setStyle($sheet);
    }

    private function mergeCell($sheet): void
    {
        // gộp cột
        $mergeCells = [
            'A1:B3', 'A4:B6', 'C1:G6', 'H1:H2', 'H3:H4', 'H5:H6',
            "I1:{$this->heightColumns}2", "I3:{$this->heightColumns}4", "I5:{$this->heightColumns}6", 'C7:G9',
        ];

        for ($i = 10; $i <= $this->heightRowInfo; ++$i) {
            $mergeCells[] = "A{$i}:B{$i}";
            $mergeCells[] = "C{$i}:G{$i}";
        }

        foreach ($mergeCells as $mergeCell) {
            $sheet->mergeCells($mergeCell);
        }
    }

    private function setCellValue($sheet): void
    {
        // Đặt giá trị vào các ô
        $sheet->setCellValue('A1', 'Công ty TNHH đầu tư công nghệ và dịch vụ Sconnect Việt Nam');
        $sheet->setCellValue('C1', 'SCONNECT');
        $sheet->setCellValue('H1', 'Ngày ban hành');
        $sheet->setCellValue('H3', 'Ngày sửa đổi');
        $sheet->setCellValue('H5', 'Lần sửa đổi');
        $sheet->setCellValue('I1', Carbon::now()->format('d-m-Y'));
        $sheet->setCellValue('I3', Carbon::now()->format('d-m-Y'));
        $sheet->setCellValue('I5', '01');
        $sheet->setCellValue('C7', $this->title);
    }

    private function setStyle($sheet): void
    {
        // set chiều rộng cho cột
        foreach (['B', 'H', 'J'] as $item) {
            $sheet->getColumnDimension($item)->setWidth(20);
        }

        //Định dạng font chữ
        foreach (['C1', 'C7'] as $item) {
            $sheet->getStyle($item)->getFont()->setBold(true)->setSize(16);
        }

        // style
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$this->heightColumns}{$highestRow}")->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
        ]);

        $sheet->getStyle("A1:{$this->heightColumns}6")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach (["A7:{$this->heightColumns}9", "A10:{$this->heightColumns}17"] as $item) {
            $sheet->getStyle($item)->applyFromArray([
                'borders' => [
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        foreach (['A1:B3', 'A4:B6', 'C1:G6', 'C1:G7'] as $item) {
            $sheet->getStyle($item)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
            ]);
        }

        foreach (["I1:{$this->heightColumns}2", "I3:{$this->heightColumns}4", "I5:{$this->heightColumns}6"] as $item) {
            $sheet->getStyle($item)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical'   => Alignment::VERTICAL_BOTTOM,
                ],
            ]);
        }
    }
}
