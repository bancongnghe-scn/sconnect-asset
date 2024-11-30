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
                'date'                  => $item?->date,
                'reason'                => $item?->reason,
                'price_liquidation'     => $item?->price_liquidation,
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
