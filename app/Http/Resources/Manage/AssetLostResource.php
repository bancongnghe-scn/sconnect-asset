<?php

namespace App\Http\Resources\Manage;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetLostResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($item) {
            return [
                'id'                => $item?->id,
                'code'              => $item?->code,
                'name'              => $item?->name,
                'user_name'         => $item?->user?->name,
                'avatar'            => $item?->user?->avatar,
                'code'              => $item?->user?->code,
                'status'            => $item?->status,
                'date'              => $item?->assetHistory?->first()?->date,
                'reason'            => $item?->assetHistory?->first()?->description,
                'location'          => Asset::LOCATION_NAME[$item?->location],
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
