<?php

namespace App\Http\Resources;

use App\Models\ShoppingPlanCompany;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Support\Constants\SOfficeConstant;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Repositories\OrganizationRepository;

class SyntheticOrganizationRegisterPlanResource extends JsonResource
{
    protected $shoppingPlanCompanyRepository;
    protected $organizationRepository;

    public function __construct(
        $resource,
    ) {
        parent::__construct($resource);
        $this->shoppingPlanCompanyRepository = new ShoppingPlanCompanyRepository();
        $this->organizationRepository        = new OrganizationRepository();
    }

    public function toArray($request)
    {
        if (ShoppingPlanCompany::STATUS_NEW === +$this->resource->status) {
            return $this->organizationRepository->getOrganizationMain()->pluck('name')->toArray();
        }

        $shoppingPlanCompany = $this->shoppingPlanCompanyRepository->getFirst([
            'id' => $this->resource->id,
        ], ['id', 'type'], [
            'shoppingPlanOrganizations:id,organization_id,shopping_plan_company_id,status,note' => [
                'shoppingAssets' => ['assetType:id,name'],
            ],
        ]);
        $organizationIds       = $shoppingPlanCompany->shoppingPlanOrganizations->pluck('organization_id')->toArray();
        $organizations         = $this->organizationRepository->getInfoOrganizationByFilters(['id' => $organizationIds])->keyBy('id')->toArray();

        if (in_array($shoppingPlanCompany->type, [ShoppingPlanCompany::TYPE_YEAR, ShoppingPlanCompany::TYPE_QUARTER])) {
            $data =  SyntheticOrganizationRegisterPlanYearQuarterResource::make($shoppingPlanCompany)->additional(['organizations' => $organizations]);
        } else {
            $data = SyntheticOrganizationRegisterPlanWeekResource::make($shoppingPlanCompany)->additional(['organizations' => $organizations]);
        }

        return $data;
    }
}
