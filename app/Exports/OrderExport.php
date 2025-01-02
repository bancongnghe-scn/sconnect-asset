<?php

namespace App\Exports;

use App\Models\AssetType;
use App\Repositories\AssetTypeRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrderExport extends BaseExport implements FromArray, WithEvents
{
    protected $id;
    protected $data;
    protected $orderRepository;
    protected $assetTypeRepository;

    public function __construct($id)
    {
        parent::__construct('Đơn hàng');
        $this->id                  = $id;
        $this->orderRepository     = new OrderRepository();
        $this->assetTypeRepository = new AssetTypeRepository();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $this->registerEventsTemplate($sheet);
                $this->setCellValueInfo($sheet);
                $this->setCellValueTable($sheet);
            },
        ];
    }

    private function setCellValueInfo($sheet): void
    {
        $configs = [
            'supplier_name'           => 'Nhà cung cấp',
            'name'                    => 'Tên đơn hàng',
            'purchasing_manager_name' => 'Người phụ trách',
            'delivery_date'           => 'Ngày giao hàng',
            'delivery_location'       => 'Địa điểm giao hàng',
            'contact_person'          => 'Người liên hệ',
            'contract_info'           => 'Thông tin liên hệ',
            'payment_time'            => 'Thời gian thanh toán',
        ];
        $row = 10;
        foreach ($configs as $key => $value) {
            $sheet->setCellValue('A'.$row, $value);
            $sheet->setCellValue('C'.$row, $this->data[$key]);
            ++$row;
        }
    }

    public function setCellValueTable($sheet): void
    {
        $configs = [
            'A' => ['name' => 'STT', 'key' => 'stt'],
            'B' => ['name' => 'Mã', 'key' => 'code'],
            'C' => ['name' => 'Tên', 'key' => 'name'],
            'D' => ['name' => 'Đơn giá', 'key' => 'price'],
            'E' => ['name' => 'VAT (%)', 'key' => 'vat_rate'],
            'F' => ['name' => 'Tiền VAT', 'key' => 'total_vat'],
            'G' => ['name' => 'Thành tiền', 'key' => 'total_price'],
            'H' => ['name' => 'Loại tài sản', 'key' => 'asset_type_name'],
            'I' => ['name' => 'ĐVT', 'key' => 'measure_name'],
            'J' => ['name' => 'Mô tả', 'key' => 'description'],
        ];
        $rowStart = $sheet->getHighestRow() + 1;
        foreach ($configs as $key => $value) {
            $sheet->setCellValue("{$key}{$rowStart}", $value['name']);
        }
        $row = $rowStart + 1;

        foreach ($this->data['assets'] as $stt => $asset) {
            foreach ($configs as $key => $value) {
                if ('A' === $key) {
                    $sheet->setCellValue('A'.$row, $stt + 1);
                    continue;
                }
                $sheet->setCellValue($key.$row, $asset[$value['key']]);
            }
            ++$row;
        }

        $sheet->getStyle("A{$rowStart}:J{$row}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
    }

    public function array(): array
    {
        $order        = $this->orderRepository->find($this->id);
        $assetOrders  = $order->shoppingAssetOrders;
        $assetTypeIds = $assetOrders->pluck('asset_type_id')->toArray();
        $assetTypes   = [];
        if (!empty($assetTypeIds)) {
            $assetTypes = $this->assetTypeRepository->getListAssetType(['id' => $assetTypeIds])->keyBy('id')->toArray();
        }
        foreach ($assetOrders as $assetOrder) {
            $assetOrder->total_vat       = +$assetOrder->price * +$assetOrder->vat_rate;
            $assetOrder->total_price     = +$assetOrder->price + (+$assetOrder->price * +$assetOrder->vat_rate);
            $assetOrder->asset_type_name = $assetTypes[$assetOrder->asset_type_id]['name'] ?? '';
            $assetOrder->measure_name    = AssetType::MEASURE_NAME[$assetTypes[$assetOrder->asset_type_id]['measure']] ?? '';
        }
        $this->data = [
            'supplier_name'           => $order->supplier?->name,
            'name'                    => $order->name,
            'purchasing_manager_name' => $order->purchasingManager?->name,
            'delivery_date'           => $order->delivery_date,
            'delivery_location'       => $order->delivery_location,
            'contact_person'          => $order->contact_person,
            'contract_info'           => $order->contract_info,
            'payment_time'            => Carbon::parse($order->payment_time)->format('d-m-Y'),
            'shipping_costs'          => $order->shipping_costs,
            'other_costs'             => $order->other_costs,
            'assets'                  => $assetOrders,
        ];

        return [];
    }
}
