<?php

namespace App\Http\Resources;

use App\Models\ShoppingPlanCompany;
use App\Services\ScApiService;
use App\Support\Constants\SOfficeConstant;
use App\Support\GraphqlQueries\OrganizationQueries;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ShoppingPlanCompanyYearInfoResource extends JsonResource
{
    public function toArray($request)
    {
        $data                = $this->resource->toArray();
        $data['monitor_ids'] = ShoppingPlanCompany::TYPE_YEAR === +$this->resource->type ? $this->resource->monitorShoppingPlanYear?->pluck('user_id')->toArray() :
            $this->resource->monitorShoppingPlanQuarter?->pluck('user_id')->toArray();

        if (ShoppingPlanCompany::STATUS_NEW === +$data['status']) {
            $response              = ScApiService::graphQl(OrganizationQueries::getOrganizationList(['status' => [SOfficeConstant::ORGANIZATION_STATUS_ACTIVE]]));
            $organizations         = Arr::get($response, 'data.OrganizationListing', []);
            $data['organizations'] = Arr::pluck($organizations, 'name');

            return $data;
        } else {
            $data['organizations'] = [];
            foreach ($this->resource->shoppingPlanOrganizations as $shoppingPlanOrganization) {
                $assetRegister = [];
                $totalPrice = 0;
                foreach ($shoppingPlanOrganization->ShoppingAssetsYear as $shoppingAsset) {
                    if (empty($assetRegister[$shoppingAsset->asset_type_id])) {
                        $assetRegister[$shoppingAsset->asset_type_id]['total_asset'] = $shoppingAsset->quantity_registered;
                        $assetRegister[$shoppingAsset->asset_type_id][$shoppingAsset->month] = [$shoppingAsset]
                    }
                    $assetRegister[$shoppingAsset->asset_type_id][$shoppingAsset->month][] = []
                }
            }
        }

        return [];
    }
}
