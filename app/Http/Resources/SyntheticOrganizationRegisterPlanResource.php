<?php

namespace App\Http\Resources;

use App\Models\ShoppingPlanCompany;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Services\ScApiService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class SyntheticOrganizationRegisterPlanResource extends JsonResource
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

        $shoppingPlanCompany = $this->shoppingPlanCompanyRepository->getFirst([
            'id' => $this->resource->id,
        ], ['id', 'type'], [
            'shoppingPlanOrganizations:id,organization_id,shopping_plan_company_id,status,note' => [
                'shoppingAssets:id,asset_type_id,quantity_registered,price,shopping_plan_organization_id,month' => [
                    'assetType:id,name',
                ],
            ],
        ]);
        $organizationIds       = $shoppingPlanCompany->shoppingPlanOrganizations->pluck('organization_id')->toArray();
        $organizations         = ScApiService::getOrganizationByIds($organizationIds);
        $organizations         = $organizations->keyBy('id')->toArray();

        if (in_array($shoppingPlanCompany->type, [ShoppingPlanCompany::TYPE_YEAR, ShoppingPlanCompany::TYPE_QUARTER])) {
            $data =  SyntheticOrganizationRegisterPlanYearQuarterResource::make($shoppingPlanCompany)->additional(['organizations' => $organizations]);
        } else {
            $data = SyntheticOrganizationRegisterPlanWeekResource::make($shoppingPlanCompany)->additional(['organizations' => $organizations]);
        }

        return $data;
    }
}
