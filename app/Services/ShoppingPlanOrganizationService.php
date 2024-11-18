<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Http\Resources\ShoppingPlanOrganizationResource;
use App\Models\ShoppingPlanCompany;
use App\Models\ShoppingPlanOrganization;
use App\Repositories\OrganizationRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use App\Repositories\UserRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
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

    public function insertShoppingPlanOrganizations($shoppingPlanCompanyId, $organizationIds = [], $status = ShoppingPlanOrganization::STATUS_OPEN_REGISTER)
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

    public function findShoppingPlanOrganization($id)
    {
        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->getInfoShoppingPlanOrganizationById($id);
        if (empty($shoppingPlanOrganization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        return [
            'success' => true,
            'data'    => ShoppingPlanOrganizationResource::make($shoppingPlanOrganization)->resolve(),
        ];
    }

    public function registerShoppingPlanOrganization($data)
    {
        $id                       = $data['shopping_plan_organization_id'];
        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->getInfoShoppingPlanOrganizationById($id, [
            'shopping_plan_company.time', 'shopping_plan_company.type', 'shopping_plan_company.plan_year_id', 'shopping_plan_company.plan_quarter_id',
            'shopping_plan_company.start_time', 'shopping_plan_company.end_time', 'shopping_plan_company.month', 'shopping_plan_company.time',
            'shopping_plan_organization.status',
        ]);
        if (empty($shoppingPlanOrganization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        // Chi dang ky khi trang thai phu hop va con thoi gian dang ky
        if (
            !in_array($shoppingPlanOrganization->status, [ShoppingPlanOrganization::STATUS_OPEN_REGISTER, ShoppingPlanOrganization::STATUS_REGISTERED])
            || Carbon::now() > Carbon::parse($shoppingPlanOrganization->end_time)
        ) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2072,
            ];
        }

        $dataNew  = [];
        $dataTime = $this->getTimeShoppingAsset($shoppingPlanOrganization);
        $metaData = array_merge($dataTime, [
            'organization_id'               => $shoppingPlanOrganization->organization_id,
            'shopping_plan_organization_id' => $shoppingPlanOrganization->id,
            'shopping_plan_company_id'      => $shoppingPlanOrganization->shopping_plan_company_id,
        ]);
        foreach ($data['registers'] as $register) {
            $month = $register['month'];
            foreach ($register['assets'] as $asset) {
                if (isset($asset['id'])) {
                    //                     $this->shoppingA
                }
            }
        }
    }

    public function getTimeShoppingAsset($shoppingPlanOrganization)
    {
        $data = [];
        switch ($shoppingPlanOrganization->type) {
            case ShoppingPlanCompany::TYPE_YEAR:
                $data = [
                    'year' => $shoppingPlanOrganization->time,
                ];
                break;
            case ShoppingPlanCompany::TYPE_QUARTER:
            case ShoppingPlanCompany::TYPE_WEEK:
                $shoppingPlanCompanyYear = $this->shoppingPlanCompanyRepository->find($shoppingPlanOrganization->plan_year_id);
                if (ShoppingPlanCompany::TYPE_QUARTER === $shoppingPlanOrganization->type) {
                    $data = [
                        'year'    => $shoppingPlanCompanyYear->time,
                        'quarter' => $shoppingPlanOrganization->time,
                    ];
                    break;
                }

                $shoppingPlanCompanyQuarter = $this->shoppingPlanCompanyRepository->find($shoppingPlanOrganization->plan_quarter_id);
                $data                       = [
                    'year'    => $shoppingPlanCompanyYear->time,
                    'quarter' => $shoppingPlanCompanyQuarter->time,
                    'month'   => $shoppingPlanOrganization->month,
                    'week'    => $shoppingPlanOrganization->time,
                ];
                break;
        }

        return [
            'success' => true,
            'data'    => $data,
        ];
    }
}
