<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class OrderExport implements FromArray, WithEvents, WithDrawings, WithTitle
{
    public function title(): string
    {
        return 'Đơn hàng';
    }

    public function array(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // gộp cột
                $sheet->mergeCells('A1:B3');
                $sheet->mergeCells('A4:B6');
                $sheet->mergeCells('C1:G6');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('H3:H4');
                $sheet->mergeCells('H5:H6');
                $sheet->mergeCells('I1:J2');
                $sheet->mergeCells('I3:J4');
                $sheet->mergeCells('I5:J6');
                $sheet->mergeCells('A7:J9');

                // set chiều rộng cho cột
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(5);


                // Đặt giá trị vào các ô
                $sheet->setCellValue('A1', 'Công ty TNHH đầu tư công nghệ và dịch vụ Sconnect Việt Nam');
                $sheet->setCellValue('C1', 'SCONNECT');
                $sheet->setCellValue('H1', 'Ngày ban hành');
                $sheet->setCellValue('H3', 'Ngày sửa đổi');
                $sheet->setCellValue('H5', 'Lần sửa đổi');
                $sheet->setCellValue('I1', '10/06/2015');
                $sheet->setCellValue('I3', '25/05/2022');
                $sheet->setCellValue('I5', '01');
                $sheet->setCellValue('A7', 'Đơn hàng');
                $sheet->setCellValue('A10', 'Nhà cung cấp');
                $sheet->setCellValue('A11', 'Tên đơn hàng');
                $sheet->setCellValue('A12', 'Người phụ trách');
                $sheet->setCellValue('A13', 'Ngày giao hàng');

                // Định dạng font chữ
                //                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                //                $sheet->getStyle('A3')->getFont()->setBold(true);

                // Tạo đường viền
                $sheet->getStyle('A1:B3')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle('A10:J13')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
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
}
