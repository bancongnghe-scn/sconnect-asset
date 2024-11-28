<?php

namespace App\Http\Resources;

use App\Models\ShoppingPlanCompany;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterShoppingYearResource extends JsonResource
{
    public function toArray($request)
    {
        $data       = [];
        $totalMonth = ShoppingPlanCompany::TYPE_YEAR === +$this->resource->shoppingPlanCompany->type ? 12 : 3;
        for ($i = 1; $i <= $totalMonth; ++$i) {
            $data[$i]['assets']   = [];
            $data[$i]['register'] = $data[$i]['approval'] = [
                'price' => 0,
                'total' => 0,
            ];
            $data[$i]['month'] = $i;
        }

        foreach ($this->resource->shoppingAssets as $shoppingAsset) {
            $data[$shoppingAsset->month]['assets'][] = $shoppingAsset;
            $data[$shoppingAsset->month]['register']['total'] += $shoppingAsset->quantity_registered;
            $data[$shoppingAsset->month]['approval']['total'] += $shoppingAsset->quantity_approved;
            $data[$shoppingAsset->month]['register']['price'] += $shoppingAsset->quantity_registered * $shoppingAsset->price;
            $data[$shoppingAsset->month]['approval']['price'] += $shoppingAsset->quantity_approved * $shoppingAsset->price;
        }

        return $data;
    }
}
