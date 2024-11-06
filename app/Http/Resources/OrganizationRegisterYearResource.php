<?php

namespace App\Http\Resources;

use App\Models\ShoppingPlanCompany;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Services\ScApiService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OrganizationRegisterYearResource extends JsonResource
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
        if (ShoppingPlanCompany::STATUS_NEW === +$this->resource->status) {
            $organizations         = ScApiService::getAllOrganizationParent();

            return Arr::pluck($organizations, 'name');
        }

        $data                    = [];
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

            $data[] = [
                'id'             => $shoppingPlanOrganization->id,
                'name'           => $organizations[$shoppingPlanOrganization->organization_id]['name'] ?? '',
                'asset_register' => empty($assetRegister) ? [[]] : $assetRegister,
                'total_price'    => $totalPrice,
            ];
        }

        return $data;
    }
}
