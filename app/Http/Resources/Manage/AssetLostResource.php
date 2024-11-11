<?php

namespace App\Http\Resources\Manage;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetLostResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($data) {
            return [
                'id'                => $data->id,
                'code'              => $data->code,
                'name'              => $data->name,
                'user_name'         => $data->user->name ?? null,
                'status'            => Asset::STATUS_NAME[$data->status],
                'lost_date'         => $data->lost_date ?? null,
                'assets_location'   => $data->assets_location ?? null,
                'lost_reason'       => $data->reason ?? null,
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
