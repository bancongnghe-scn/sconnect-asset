<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Http\Resources\RegisterShoppingYearResource;
use App\Http\Resources\ShoppingPlanOrganizationResource;
use App\Models\ShoppingPlanCompany;
use App\Models\ShoppingPlanLog;
use App\Models\ShoppingPlanOrganization;
use App\Repositories\OrganizationRepository;
use App\Repositories\ShoppingAssetRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanLogRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use App\Repositories\UserRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShoppingPlanOrganizationService
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected ShoppingPlanOrganizationRepository $shoppingPlanOrganizationRepository,
        protected ShoppingPlanCompanyRepository $shoppingPlanCompanyRepository,
        protected UserRepository $userRepository,
        protected ShoppingAssetRepository $shoppingAssetRepository,
        protected ShoppingPlanLogRepository $shoppingPlanLogRepository,
    ) {
    }

    public function insertShoppingPlanOrganizations($shoppingPlanCompanyId, $organizationIds = [], $status = ShoppingPlanOrganization::STATUS_OPEN_REGISTER)
    {
        if (empty($organizationIds)) {
            $organizations   = ScApiService::getAllOrganizationParent();
            $organizationIds = Arr::pluck($organizations, 'id');
        }

        $dataInsert = [];
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
        $planOrganization = $this->shoppingPlanOrganizationRepository->getListingOfOrganization($filters, $deptId);

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
        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->getInfoShoppingPlanOrganizationById($id, [
            'shopping_plan_company.name', 'shopping_plan_company.start_time', 'shopping_plan_company.end_time', 'shopping_plan_company.status as status_company',
            'shopping_plan_organization.organization_id', 'shopping_plan_organization.status', 'shopping_plan_organization.id',
        ]);
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
        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->find($id);
        if (empty($shoppingPlanOrganization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        // Chi dang ky khi trang thai phu hop va con thoi gian dang ky
        $shoppingPlanCompany = $shoppingPlanOrganization->shoppingPlanCompany;
        if (
            !in_array($shoppingPlanOrganization->status, [ShoppingPlanOrganization::STATUS_OPEN_REGISTER, ShoppingPlanOrganization::STATUS_REGISTERED])
            || Carbon::now() > Carbon::parse($shoppingPlanCompany->end_time)
        ) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2072,
            ];
        }

        $userId           = Auth::id();
        $dataNew          = [];
        $shoppingAssetIds = [];
        $dataTime         = $this->getTimeShoppingAsset($shoppingPlanCompany);
        $metaData         = array_merge($dataTime, [
            'organization_id'               => $shoppingPlanOrganization->organization_id,
            'shopping_plan_organization_id' => $id,
            'shopping_plan_company_id'      => $shoppingPlanOrganization->shopping_plan_company_id,
            'created_by'                    => $userId,
        ]);
        $isTypeYearOrQuarter = in_array($shoppingPlanCompany->type, [ShoppingPlanCompany::TYPE_YEAR, ShoppingPlanCompany::TYPE_QUARTER]);

        DB::beginTransaction();
        try {
            if (ShoppingPlanOrganization::STATUS_OPEN_REGISTER === +$shoppingPlanOrganization->status) {
                $shoppingPlanOrganization->status = ShoppingPlanOrganization::STATUS_REGISTERED;
                if (!$shoppingPlanOrganization->save()) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2073,
                    ];
                }
            }

            foreach ($data['registers'] as $register) {
                if ($isTypeYearOrQuarter) {
                    $metaData['month'] = $register['month'];
                }

                foreach ($register['assets'] as $asset) {
                    if (!isset($asset['id'])) {
                        $dataNew[] = array_merge($asset, $metaData);
                        continue;
                    }

                    $id                 = $asset['id'];
                    $shoppingAssetIds[] = $id;
                    unset($asset['id']);
                    $this->shoppingAssetRepository->update($id, array_merge($asset, [
                        'updated_by' => $userId,
                    ]));
                }
            }

            // xoa cac tai san
            $shoppingAssetsOldIds = $shoppingPlanOrganization->shoppingAssets->pluck('id')->toArray();
            $removedIds           = array_diff($shoppingAssetsOldIds, $shoppingAssetIds);
            if (!empty($removedIds)) {
                $this->shoppingAssetRepository->deleteByIds($removedIds);
            }

            if (!empty($dataNew)) {
                $insert = $this->shoppingAssetRepository->insert($dataNew);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2073,
                    ];
                }
            }

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_REGISTER_SHOPPING,
                $data['shopping_plan_organization_id']
            );
            if (!$insertLog) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }

            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getTimeShoppingAsset($shoppingPlanCompany)
    {
        $data = [];
        switch ($shoppingPlanCompany->type) {
            case ShoppingPlanCompany::TYPE_YEAR:
                $data = [
                    'year' => $shoppingPlanCompany->time,
                ];
                break;
            case ShoppingPlanCompany::TYPE_QUARTER:
            case ShoppingPlanCompany::TYPE_WEEK:
                $shoppingPlanCompanyYear = $this->shoppingPlanCompanyRepository->find($shoppingPlanCompany->plan_year_id);
                if (ShoppingPlanCompany::TYPE_QUARTER == $shoppingPlanCompany->type) {
                    $data = [
                        'year'    => $shoppingPlanCompanyYear->time,
                        'quarter' => $shoppingPlanCompany->time,
                    ];
                    break;
                }

                $shoppingPlanCompanyQuarter = $this->shoppingPlanCompanyRepository->find($shoppingPlanCompany->plan_quarter_id);
                $data                       = [
                    'year'    => $shoppingPlanCompanyYear->time,
                    'quarter' => $shoppingPlanCompanyQuarter->time,
                    'month'   => $shoppingPlanCompany->month,
                    'week'    => $shoppingPlanCompany->time,
                ];
                break;
        }

        return $data;
    }

    public function getRegisterShoppingPlanOrganization($id)
    {
        // kiem tra ton tai
        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->find($id);
        if (empty($shoppingPlanOrganization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }
        // kiem tra loai ke hoach de tra ve format phu hop
        if (ShoppingPlanCompany::TYPE_WEEK === $shoppingPlanOrganization->shoppingPlanCompany->type) {
            $data = [];
        } else {
            // neu la kh nam va quy thi cung 1 format
            $data = RegisterShoppingYearResource::make($shoppingPlanOrganization)->resolve();
        }

        return [
            'success' => true,
            'data'    => $data,
        ];
    }

    public function accountApprovalShoppingPlanOrganization($data)
    {
        if (ShoppingPlanOrganization::TYPE_APPROVAL == $data['type']) {
            $status = ShoppingPlanOrganization::STATUS_PENDING_MANAGER_APPROVAL;
            $action = ShoppingPlanLog::ACTION_ACCOUNT_APPROVAL_ORGANIZATION;
        } else {
            $status = ShoppingPlanOrganization::STATUS_ACCOUNT_DISAPPROVAL;
            $action = ShoppingPlanLog::ACTION_ACCOUNT_DISAPPROVAL_ORGANIZATION;
        }

        DB::beginTransaction();
        try {
            $this->shoppingPlanOrganizationRepository->updateShoppingPlanOrganization(
                ['ids' => $data['ids']],
                [
                    'status' => $status,
                    'note'   => $data['note'] ?? null]
            );

            $dataInsertLogs = [];
            foreach ($data['ids'] as $id) {
                $dataInsertLogs[] = [
                    'action'     => $action,
                    'record_id'  => $id,
                    'desc'       => ShoppingPlanLog::ACTION_ACCOUNT_APPROVAL_ORGANIZATION == $action ?
                        __('shopping_plan_log.' . $action) : __('shopping_plan_log.' . $action, ['note' => $data['note'] ?? null]),
                    'created_by' => Auth::id(),
                ];
            }

            $insert = $this->shoppingPlanLogRepository->insert($dataInsertLogs);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function saveTotalAssetApproval($data)
    {
        // kiem tra ton tai
        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->find($data['id']);
        if (empty($shoppingPlanOrganization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (!in_array($shoppingPlanOrganization->status, [
            ShoppingPlanOrganization::STATUS_PENDING_ACCOUNTANT_APPROVAL,
            ShoppingPlanOrganization::STATUS_ACCOUNTANT_REVIEWING,
        ])) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2074,
            ];
        }

        DB::beginTransaction();
        try {
            if (ShoppingPlanOrganization::STATUS_PENDING_ACCOUNTANT_APPROVAL == $shoppingPlanOrganization->status) {
                $shoppingPlanOrganization->status = ShoppingPlanOrganization::STATUS_ACCOUNTANT_REVIEWING;
                if (!$shoppingPlanOrganization->save()) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2062,
                    ];
                }
            }

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_ACCOUNT_REVIEW_ORGANIZATION,
                $shoppingPlanOrganization->id
            );
            if (!$insertLog) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }

            foreach ($data['registers'] as $register) {
                foreach ($register['assets'] as $asset) {
                    $this->shoppingAssetRepository->update($asset['id'], [
                        'quantity_approved' => $asset['quantity_approved'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
