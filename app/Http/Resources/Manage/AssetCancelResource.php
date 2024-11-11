<?php

namespace App\Http\Resources\Manage;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetCancelResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($data) {
            return [
                'code'                => $data->code,
                'name'                => $data->name,
                'user_name'           => $data->user->name ?? null,
                'status'              => Asset::STATUS_NAME[$data->status],
                'cancel_date'         => $data->cancel_date ?? null,
                'assets_location'     => $data->assets_location ?? null,
                'cancel_reason'       => $data->cancel_reason ?? null,
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
