<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListAssetTypeResource extends JsonResource
{
    public function toArray($request) {
        $data = [];
        foreach ($this->resource as $assetType) {
            $data[] = [
                'id' => $assetType->id,
                'name' => $assetType->name,
                'description' => $assetType->description,
                'maintenance_months' => $assetType->maintenance_months,
                'asset_type_group' => $assetType->assetTypeGroup?->name
            ];
        }

        $listAssetType = $this->resource->toArray();
        if (isset($listAssetType['total'])) {
            return [
                'data' => $data,
                'total' => $listAssetType['total'],
                'last_page' => $listAssetType['last_page'],
                'current_page' => $listAssetType['current_page'],
            ];
        }

        return [
            'data' => $data,
        ];
    }
}
