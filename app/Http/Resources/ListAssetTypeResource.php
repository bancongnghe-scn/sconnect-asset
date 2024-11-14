<?php

namespace App\Http\Resources;

use App\Models\AssetType;
use Illuminate\Http\Resources\Json\JsonResource;

class ListAssetTypeResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $assetType) {
            $data[] = [
                'id'                 => $assetType->id,
                'name'               => $assetType->name,
                'description'        => $assetType->description,
                'maintenance_months' => $assetType->maintenance_months,
                'measure'            => AssetType::MEASURE_NAME[$assetType->measure] ?? '',
                'asset_type_group'   => $assetType->assetTypeGroup?->name,
            ];
        }

        $listAssetType = $this->resource->toArray();
        if (isset($listAssetType['total'])) {
            $listAssetType['data'] = $data;

            return $listAssetType;
        }

        return $data;
    }
}
