<?php

namespace App\Services;

use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationService
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected ShoppingPlanOrganizationRepository $shoppingPlanOrganizationRepository,
    ) {
    }

    public function insertShoppingPlanOrganizations($shoppingPlanCompanyId, $organizationIds = [])
    {
        if (empty($organizationIds)) {
            $organizationIds = $this->organizationRepository->all()->pluck('id')->toArray();
        }

        $dataInsert  = [];
        foreach ($organizationIds as $organizationId) {
            $dataInsert[] = [
                'status'                   => Organization::STATUS_REGISTER,
                'organization_id'          => $organizationId,
                'shopping_plan_company_id' => $shoppingPlanCompanyId,
                'manager_id'               => $organizationId,
                'created_by'               => Auth::id(),
            ];
        }

        return $this->shoppingPlanOrganizationRepository->insert($dataInsert);
    }
}
