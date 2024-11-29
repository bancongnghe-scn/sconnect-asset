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
                'status'            => Asset::STATUS_NAME[$item?->status],
                'date'              => $item?->date,
                'location'          => $item?->location,
                'reason'            => $item?->reason,
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
