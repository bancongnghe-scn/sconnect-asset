<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExport implements WithEvents
{
    private $spreadsheet;
    private $sheet;

    public function __construct()
    {
        // 📌 Load file Excel template từ thư mục storage
        $this->spreadsheet = IOFactory::load(storage_path('app/template/thuhoi.xlsx'));

        // 📌 Lấy sheet đầu tiên của template
        $this->sheet = $this->spreadsheet->getSheet(0);
    }

    /**
     * ✅ Trả về nội dung của file template (bắt buộc để Laravel Excel nhận diện).
     */
    public function array(): array
    {
        return [];
    }

    /**
     * ✅ Sao chép dữ liệu từ template & chèn dữ liệu động.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setSelectedCell('A1');

                // ✅ Copy toàn bộ nội dung từ template sang sheet export
                self::copySheet($this->sheet, $event->sheet->getDelegate());

                // ✅ Chèn dữ liệu từ database vào file Excel
                self::insertData($event->sheet->getDelegate());
            },
        ];
    }

    /**
     * ✅ Hàm sao chép toàn bộ nội dung, styles, công thức từ template.
     */
    private static function copySheet(Worksheet $source, Worksheet $destination)
    {
        foreach ($source->getCellCollection() as $cell) {
            $cellValue = $source->getCell($cell)->getValue();
            $destination->setCellValue($cell, $cellValue);

            // ✅ Sao chép công thức (nếu có)
            if (\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_FORMULA === $source->getCell($cell)->getDataType()) {
                $destination->getCell($cell)->setValue($source->getCell($cell)->getValue());
            }

            // ✅ Sao chép định dạng (font, màu nền, border, căn lề)
            $destination->getStyle($cell)->applyFromArray([
                'font'      => $source->getStyle($cell)->getFont()->toArray(),
                'alignment' => $source->getStyle($cell)->getAlignment()->toArray(),
                'fill'      => $source->getStyle($cell)->getFill()->toArray(),
                'borders'   => $source->getStyle($cell)->getBorders()->toArray(),
            ]);
        }
    }

    /**
     * ✅ Hàm chèn dữ liệu động vào các vị trí trong Excel.
     */
    private static function insertData(Worksheet $sheet)
    {
        // $users = User::all();
        // $startRow = 5; // 📌 Dòng bắt đầu chèn dữ liệu (tùy vào template của bạn)

        // foreach ($users as $index => $user) {
        //     $row = $startRow + $index;
        //     $sheet->setCellValue('A' . $row, $index + 1);
        //     $sheet->setCellValue('B' . $row, $user->name);
        //     $sheet->setCellValue('C' . $row, $user->email);
        //     $sheet->setCellValue('D' . $row, $user->created_at->format('d/m/Y'));
        // }
    }
}
