<?php

namespace App\Http\Resources\Manage;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetLiquidationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($item) {
            return [
                'id'                    => $item?->id,
                'code'                  => $item?->code,
                'name'                  => $item?->name,
                'status'                => Asset::STATUS_NAME[$item?->status],
                'date'                  => $item?->assetHistory?->first()?->date,
                'reason'                => $item?->assetHistory?->first()?->description,
                'price_liquidation'     => $item?->assetHistory?->first()?->price,
            ];
        });

        return [
            'data'         => $data->toArray(),
            'current_page' => $this->resource->currentPage(),
            'last_page'    => $this->resource->lastPage(),
            'per_page'     => $this->resource->perPage(),
            'total'        => $this->resource->total(),
        ];
    }
}
