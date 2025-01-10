<?php

namespace App\Http\Resources\Manage;

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
                'status'                => $item?->status,
                'date'                  => $item?->assetHistory?->first()?->date,
                'reason'                => $item?->assetHistory?->first()?->description,
                'price_liquidation'     => $item?->assetHistory?->first()?->price,
            ];
        });


        $result = $this->resource->toArray();
        if (isset($result['total'])) {
            $result['data'] = $data->toArray();

            return $result;
        }

        return $data;
    }
}
