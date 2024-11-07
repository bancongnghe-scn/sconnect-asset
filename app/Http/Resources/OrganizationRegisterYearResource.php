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

        $data = [
            'organizations'       => [],
            'total_price_months'  => [],
            'total_price_company' => 0,
        ];

        $shoppingPlanCompanyYear = $this->shoppingPlanCompanyRepository->getFirst([
            'id' => $this->resource->id,
        ], ['id'], [
            'shoppingPlanOrganizations:id,organization_id,shopping_plan_company_id' => [
                'shoppingAssetsYear:id,asset_type_id,quantity_registered,price,shopping_plan_organization_id,month' => [
                    'assetType:id,name',
                ],
            ],
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
                    $assetRegister[$shoppingAsset->asset_type_id]['asset_type_name'] = $shoppingAsset->assetType?->name;
                    $assetRegister[$shoppingAsset->asset_type_id]['register']        = [];
                }

                $assetRegister[$shoppingAsset->asset_type_id]['total_register'] += $shoppingAsset->quantity_registered;

                $price = $shoppingAsset->quantity_registered * $shoppingAsset->price;
                $totalPrice += $price;
                if (empty($data['total_price_months'][$shoppingAsset->month])) {
                    $data['total_price_months'][$shoppingAsset->month] = 0;
                }
                $data['total_price_months'][$shoppingAsset->month] += $price;

                if (empty($assetRegister[$shoppingAsset->asset_type_id]['register'][$shoppingAsset->month])) {
                    $assetRegister[$shoppingAsset->asset_type_id]['register'][$shoppingAsset->month] = 0;
                }
                $assetRegister[$shoppingAsset->asset_type_id]['register'][$shoppingAsset->month] += $shoppingAsset->quantity_registered;
            }

            $data['organizations'][] = [
                'id'             => $shoppingPlanOrganization->id,
                'name'           => $organizations[$shoppingPlanOrganization->organization_id]['name'] ?? '',
                'asset_register' => empty($assetRegister) ? [[]] : $assetRegister,
                'total_price'    => $totalPrice,
            ];

            $data['total_price_company'] = $data['total_price_company'] + $totalPrice;
        }

        return $data;
    }
}
