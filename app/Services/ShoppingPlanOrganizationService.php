<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Models\ShoppingPlanOrganization;
use App\Repositories\OrganizationRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanOrganizationService
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected ShoppingPlanOrganizationRepository $shoppingPlanOrganizationRepository,
        protected ShoppingPlanCompanyRepository $shoppingPlanCompanyRepository,
        protected UserRepository $userRepository,
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

    public function getListShoppingPlanOrganization(array $filters, $deptId = null)
    {
        if (is_null($deptId)) {
            $deptId = Auth::user()->dept_id;
        }
        $planOrganization = $this->shoppingPlanCompanyRepository->getListingOfOrganization($filters, $deptId);

        if ($planOrganization->isEmpty()) {
            return [];
        }

        $userIds = $planOrganization->pluck('created_by')->toArray();
        $users   = $this->userRepository->getListing(['id' => $userIds], ['id', 'name', 'code']);

        return [
            'data' => ListShoppingPlanCompanyResource::make($planOrganization)
                ->additional([
                    'users' => $users->keyBy('id'),
                ])->resolve(),
        ];
    }
}
