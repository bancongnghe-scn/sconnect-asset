<?php

namespace App\Services;

use App\Models\ShoppingPlanOrganization;
use App\Repositories\OrganizationRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationService
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected ShoppingPlanOrganizationRepository $shoppingPlanOrganizationRepository,
    ) {
    }

    public function insertShoppingPlanOrganizations($shoppingPlanCompanyId, $organizationIds = [], $status = ShoppingPlanOrganization::STATUS_REGISTER)
    {
        if (empty($organizationIds)) {
            $organizations   = ScApiService::getAllOrganizationParent();
            $organizationIds = Arr::pluck($organizations, 'id');
        }

        $dataInsert  = [];
        foreach ($organizationIds as $organizationId) {
            $dataInsert[] = [
                'status'                   => $status,
                'organization_id'          => $organizationId,
                'shopping_plan_company_id' => $shoppingPlanCompanyId,
                'created_by'               => Auth::id(),
            ];
        }

        return $this->shoppingPlanOrganizationRepository->insert($dataInsert);
    }
}
