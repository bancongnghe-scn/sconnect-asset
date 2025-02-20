<?php

namespace App\Http\Resources\Manage;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetLostResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($item) {
            return [
                'id'                     => $item?->id,
                'code'                   => $item?->code,
                'name'                   => $item?->name,
                'user'                   => $item?->user,
                'status'                 => $item?->status,
                'date'                   => $item?->assetHistory?->first()?->date,
                'reason'                 => $item?->assetHistory?->first()?->description,
                'location'               => $item->location,
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
