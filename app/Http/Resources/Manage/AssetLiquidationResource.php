<?php

namespace App\Http\Resources\Manage;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetLiquidationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($data) {
            return [
                'id'                    => $data->id,
                'code'                  => $data->code,
                'name'                  => $data->name,
                'status'                => Asset::STATUS_NAME[$data->status],
                'date'                  => $data->date ?? null,
                'reason'                => $data->reason ?? null,
                'price_liquidation'     => $data->price_liquidation ?? null,
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
