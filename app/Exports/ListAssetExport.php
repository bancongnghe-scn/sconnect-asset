<?php

namespace App\Exports;

use App\Models\Asset;
use App\Traits\ExportStylingTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ListAssetExport implements FromArray, WithHeadings, WithEvents
{
    use ExportStylingTrait;

    public function __construct()
    {
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã tài sản',
            'Tên tài sản',
            'Loại tài sản',
            'Đơn vị',
            'Nhân viên đang sử dụng',
            'Người đại diện',
            'Ngày mua',
            'Vị trí',
            'Số seri',
            'Giá trị',
            'Ngày bảo dưỡng gần nhất',
            'Ngày bảo dưỡng tiếp theo',
            'Hạn bảo hành',
            'Thời gian bảo hành',
        ];
    }

    public function array(): array
    {
        $listAsset = Asset::with([
            'user',
            'user.organization',
            'user.organization.deptType',
            'assetType',
            'organization',
            'organization.manager',
            'organization.deptType',
        ])->orderBy('id', 'desc')->get();

        foreach ($listAsset as $key => $asset) {
            $listAssetExport[] = [
                'order'                   => $key + 1,
                'code'                    => $asset->code,
                'name'                    => $asset->name,
                'type'                    => $asset->assetType?->name,
                'org'                     => $asset->organization ? $asset->organization?->name : ($asset->user ? $asset->user?->organization?->name : ''),
                'user'                    => $asset->user ? $asset->user->name : '',
                'manager'                 => !$asset->user ? $asset->organization?->manager->name : '',
                'date_purchase'           => Carbon::parse($asset->date_purchase)->format('d/m/Y'),
                'location'                => $asset->location ? $asset->location_text : '',
                'seri_number'             => $asset->seri_number.'',
                'price'                   => $asset->price,
                'recent_maintenance_date' => $asset->recent_maintenance_date,
                'next_maintenance_date'   => $asset->next_maintenance_date,
                'month_warranty'          => $asset->warranty_months,

                'time_warranty' => Carbon::parse($asset->date_warranty)->format('d/m/Y'),
            ];
        }

        return $listAssetExport;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet         = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                // Apply header style using trait
                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray($this->headerStyle());

                // Áp dụng auto-size và wrap-text cùng lúc
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $dimension = $sheet->getColumnDimension($column);
                    $dimension->setAutoSize(true); // Tự động căn chỉnh
                }
            },
        ];
    }
}
