<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListImportWarehouseAssetResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $key => $value) {
            $data[] = [
                'code'                => $value->code,
                'name'                => $value->name,
                'price'               => $value->price,
                'date_purchase'       => $value->date_purchase ?? null,
                'warranty_time'       => $value->warranty_time ?? null,
                'seri_number'         => $value->seri_number ?? null,
                'asset_type_id'       => $value->asset_type_id,
                'supplier_id'         => $value->supplier_id,
                'order_id'            => $value->order_id,
                'import_warehouse_id' => $value->import_warehouse_id ?? null,
            ];
        }

        return $data;
    }
}
