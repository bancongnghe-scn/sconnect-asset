<?php

namespace App\Http\Resources;

use App\Models\OrderHistory;
use App\Repositories\AssetTypeRepository;
use App\Repositories\OrderHistoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SupplierRepository;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ListImportWarehouseAssetResource extends JsonResource
{
    protected $orderRepository;
    protected $supplierRepository;
    protected $assetTypeRepository;
    protected $orderHistoryRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->orderRepository        = new OrderRepository();
        $this->supplierRepository     = new SupplierRepository();
        $this->assetTypeRepository    = new AssetTypeRepository();
        $this->orderHistoryRepository = new OrderHistoryRepository();
    }

    public function toArray($request)
    {
        $data      = [];
        $suppliers = $this->supplierRepository->getListing(['ids' => $this->resource->pluck('supplier_id')->toArray()])->keyBy('id');
        $assetType = $this->assetTypeRepository->getListAssetType(['id' => $this->resource->pluck('asset_type_id')->toArray()])->keyBy('id');
        $asset     = $this->resource->first();
        if (isset($asset->import_warehouse_id)) {
            foreach ($this->resource as &$value) {
                $value['supplier_name']   = $suppliers[$value->supplier_id]->name ?? null;
                $value['asset_type_name'] = $assetType[$value->asset_type_id]->name ?? null;
                $value['measure']         = $assetType[$value->asset_type_id]->measure ?? null;
            }

            return $this->resource;
        }

        $order             = $this->orderRepository->find($asset->order_id);
        $orderHistory      = $this->orderHistoryRepository->getListing(['order_id' => $order->id, 'type' => OrderHistory::TYPE_COMPLETE_ORDER, 'first' => true]);
        $dateCompleteOrder = Carbon::parse($orderHistory?->created_at)->format('Y-m-d');
        $totalCost         = (+$order->shipping_costs) + (+$order->other_costs);
        $totalPrice        = 0;
        foreach ($this->resource as $key => $value) {
            $code       = strtoupper(Str::random(7)).$key;
            $price      = +$value->price + ($value->price * $value->vate_rate ?? 0);
            $totalPrice = $totalPrice + $price;
            $data[]     = [
                'code'                  => $code,
                'name'                  => $value->name,
                'price'                 => $price,
                'date_purchase'         => $dateCompleteOrder,
                'warranty_time'         => null,
                'seri_number'           => null,
                'asset_type_id'         => $value->asset_type_id,
                'measure'               => $assetType[$value->asset_type_id]->measure ?? null,
                'asset_type_name'       => $assetType[$value->asset_type_id]->name ?? null,
                'supplier_id'           => $value->supplier_id,
                'supplier_name'         => $suppliers[$value->supplier_id]->name ?? null,
                'order_id'              => $value->order_id,
                'import_warehouse_id'   => $value->import_warehouse_id ?? null,
            ];
        }

        foreach ($data as &$value) {
            $value['price_last'] = (+$value['price']) + ((+$value['price'] / $totalPrice) * $totalCost);
        }

        return $data;
    }
}
