<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfoImportWarehouseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->resource->id,
            'name'        => $this->resource->name,
            'code'        => $this->resource->code,
            'description' => $this->resource->description,
            'order_ids'   => $this->resource->importWarehouseOrders?->pluck('order_id')->toArray(),
        ];
    }
}
