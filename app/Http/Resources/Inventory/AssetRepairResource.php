<?php

namespace App\Http\Resources\Inventory;

use App\Models\AssetRepair;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetRepairResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($data) {
            return [
                'id'                    => $data->id,
                'asset_id'              => $data?->asset?->id,
                'asset_name'            => $data?->asset?->name,
                'asset_code'            => $data?->asset?->code,
                'asset_reason'          => $data?->asset?->assetHistory?->first()?->description,
                'status_repair'         => AssetRepair::STATUS_NAME[$data->status],
                'date_repair'           => $data->date_repair,
                'date'                  => $data?->asset?->assetHistory?->first()?->date,
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
