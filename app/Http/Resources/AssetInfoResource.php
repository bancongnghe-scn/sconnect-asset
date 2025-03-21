<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->resource->id,
            'name'          => $this->resource->name,
            'code'          => $this->resource->code,
            'asset_type'    => $this->resource->assetType?->name,
            'measure'       => $this->resource->assetType?->measure,
            'supplier'      => $this->resource->supplier?->name,
            'price'         => $this->resource->price,
            'date_purchase' => $this->resource->date_purchase,
            'seri_number'   => $this->resource->seri_number,
            'location'      => $this->resource->location,
            'status'        => $this->resource->status,
        ];
    }
}
