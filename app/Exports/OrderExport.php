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
                $sheet->mergeCells('A10:J23');

                // Đặt giá trị vào các ô
                $sheet->getDelegate()->setCellValue('A1', 'Công ty TNHH đầu tư công nghệ và dịch vụ Sconnect Việt Nam');
                $sheet->getDelegate()->setCellValue('C1', 'SCONNECT');
                $sheet->getDelegate()->setCellValue('H1', 'Ngày ban hành');
                $sheet->getDelegate()->setCellValue('H3', 'Ngày sửa đổi');
                $sheet->getDelegate()->setCellValue('H5', 'Lần sửa đổi');
                $sheet->getDelegate()->setCellValue('I1', '10/06/2015');
                $sheet->getDelegate()->setCellValue('I3', '25/05/2022');
                $sheet->getDelegate()->setCellValue('I5', '01');
                $sheet->getDelegate()->setCellValue('A7', 'Đơn hàng');
                $sheet->getDelegate()->setCellValue('A10', 'Nhà cung cấp');
                $sheet->getDelegate()->setCellValue('A11', 'Tên đơn hàng');
                $sheet->getDelegate()->setCellValue('A12', 'Người phụ trách');
                $sheet->getDelegate()->setCellValue('A13', 'Ngày giao hàng');

                // Định dạng font chữ
                //                $sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                //                $sheet->getDelegate()->getStyle('A3')->getFont()->setBold(true);

                // Tạo đường viền
                //                $sheet->getDelegate()->getStyle('A6:G10')->applyFromArray([
                //                    'borders' => [
                //                        'allBorders' => [
                //                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //                        ],
                //                    ],
                //                ]);
            },
        ];
        //        return [
        //            AfterSheet::class => function (AfterSheet $event) {
        //                $sheet = $event->sheet->getDelegate();
        //
        //                // Gộp ô cả chiều ngang và dọc (A1 đến B3)
        //                $sheet->mergeCells('A1:B3');
        //
        //                // Đặt giá trị vào ô đã gộp
        //                $sheet->setCellValue('A1', 'Gộp cả ngang và dọc');
        //            },
        //        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is the company logo.');
        $drawing->setPath(public_path('images/logo-s.png')); // Thay đường dẫn ảnh của bạn
        $drawing->setHeight(50);
        $drawing->setCoordinates('A4');

        return $drawing;
    }
}
