<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListShoppingAssetOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $data          = [];
        $assetTypes    = $this->additional['asset_types'] ?? [];
        $organizations = $this->additional['organizations'] ?? [];
        foreach ($this->resource as $shoppingAssetOrder) {
            $data[] = [
                'id'                => $shoppingAssetOrder->id,
                'code'              => $shoppingAssetOrder->code,
                'name'              => $shoppingAssetOrder->name,
                'price'             => $shoppingAssetOrder->price,
                'vat_rate'          => $shoppingAssetOrder->vat_rate,
                'description'       => $shoppingAssetOrder->description,
                'asset_type_id'     => $shoppingAssetOrder->asset_type_id,
                'organization_id'   => $shoppingAssetOrder->organization_id,
                'organization_name' => $organizations[$shoppingAssetOrder->organization_id]['name'] ?? null,
                'asset_type_name'   => $assetTypes[$shoppingAssetOrder->asset_type_id]['name'] ?? null,
                'measure'           => $assetTypes[$shoppingAssetOrder->asset_type_id]['measure'] ?? null,
            ];
        }

        return $data;
    }
}
