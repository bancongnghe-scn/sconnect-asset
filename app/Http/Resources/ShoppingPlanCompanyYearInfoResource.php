<?php

namespace App\Http\Resources;

use App\Models\ShoppingPlanCompany;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Services\ScApiService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ShoppingPlanCompanyYearInfoResource extends JsonResource
{
    protected $shoppingPlanCompanyRepository;

    public function __construct(
        $resource,
    ) {
        parent::__construct($resource);
        $this->shoppingPlanCompanyRepository = new ShoppingPlanCompanyRepository();
    }

    public function toArray($request)
    {
        $data                = $this->resource->toArray();
        $data['monitor_ids'] = ShoppingPlanCompany::TYPE_YEAR === +$this->resource->type ? $this->resource->monitorShoppingPlanYear?->pluck('user_id')->toArray() :
            $this->resource->monitorShoppingPlanQuarter?->pluck('user_id')->toArray();

        if (ShoppingPlanCompany::STATUS_NEW === +$data['status']) {
            $organizations         = ScApiService::getAllOrganizationParent();
            $data['organizations'] = Arr::pluck($organizations, 'name');
        } else {
            $data['organizations']   = [];
            $shoppingPlanCompanyYear = $this->shoppingPlanCompanyRepository->getFirst([
                'id' => $this->resource->id,
            ], with: [
                'shoppingPlanOrganizations' => ['shoppingAssetsYear'],
            ]);
            $organizationIds       = $shoppingPlanCompanyYear->shoppingPlanOrganizations->pluck('organization_id')->toArray();
            $organizations         = ScApiService::getOrganizationByIds($organizationIds);
            $organizations         = $organizations->keyBy('id')->toArray();
            foreach ($shoppingPlanCompanyYear->shoppingPlanOrganizations as $shoppingPlanOrganization) {
                $assetRegister = [];
                $totalPrice    = 0;
                foreach ($shoppingPlanOrganization->shoppingAssetsYear as $shoppingAsset) {
                    if (!isset($assetRegister[$shoppingAsset->asset_type_id])) {
                        $assetRegister[$shoppingAsset->asset_type_id]['total_register']  = 0;
                        $assetRegister[$shoppingAsset->asset_type_id]['asset_type_name'] = 'test';
                    }

                    $assetRegister[$shoppingAsset->asset_type_id]['register'][] = $shoppingAsset->quantity_registered;
                    $assetRegister[$shoppingAsset->asset_type_id]['total_register']
                                = $shoppingAsset->quantity_registered + $assetRegister[$shoppingAsset->asset_type_id]['total_register'];
                    $totalPrice = $totalPrice + $shoppingAsset->quantity_registered * $shoppingAsset->price;
                }

                $data['organizations'][] = [
                    'id'             => $shoppingPlanOrganization->id,
                    'name'           => $organizations[$shoppingPlanOrganization->organization_id]['name'] ?? '',
                    'asset_register' => $assetRegister,
                    'total_price'    => $totalPrice,
                ];
            }
        }

        return $data;
    }
}
