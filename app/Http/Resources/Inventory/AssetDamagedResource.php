<?php

namespace App\Http\Resources\Inventory;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetDamagedResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($data) {
            return [
                'id'                    => $data->id,
                'code'                  => $data->code,
                'name'                  => $data->name,
                'user_name'             => $data?->user?->name,
                'status'                => Asset::STATUS_NAME[$data->status],
                'location'              => Asset::LOCATION_NAME[$data?->location],
                'date'                  => $data?->assetHistory?->first()?->date,
                'reason'                => $data?->assetHistory?->first()?->description,
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
