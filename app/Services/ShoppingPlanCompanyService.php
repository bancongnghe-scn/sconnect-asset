<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Http\Resources\OrganizationRegisterYearResource;
use App\Models\Monitor;
use App\Models\ShoppingPlanCompany;
use App\Models\ShoppingPlanOrganization;
use App\Repositories\MonitorRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use App\Repositories\UserRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShoppingPlanCompanyService
{
    public function __construct(
        protected ShoppingPlanCompanyRepository $planCompanyRepository,
        protected UserRepository $userRepository,
        protected MonitorRepository $monitorRepository,
        protected ShoppingPlanOrganizationRepository $shoppingPlanOrganizationRepository,
    ) {

    }

    public function getListShoppingPlanCompany(array $filters)
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['accounting_department', 'personnel_department'])) {
            $planCompany = $this->getShoppingPlanCompanyOfMonitor($filters, $user->id);
        } else {
            $planCompany = $this->planCompanyRepository->getListing($filters, [
                'id', 'name', 'time',
                'start_time', 'end_time', 'plan_year_id',
                'status', 'created_by', 'created_at',
            ]);
        }

        if ($planCompany->isEmpty()) {
            return [];
        }

        $userIds = $planCompany->pluck('created_by')->toArray();
        $users   = $this->userRepository->getListing(['id' => $userIds], ['id', 'name', 'code']);

        return [
            'data' => ListShoppingPlanCompanyResource::make($planCompany)
                ->additional([
                    'users' => $users->keyBy('id'),
                ])->resolve(),
        ];
    }

    public function createShoppingPlanCompany(array $data)
    {
        $checkExist = $this->checkExistShoppingPlanCompany($data);
        if (!$checkExist['success']) {
            return $checkExist;
        }

        $data['name']       = $this->getNameShoppingPlanCompany($data);
        $data['status']     = ShoppingPlanCompany::STATUS_NEW;
        $data['created_by'] = Auth::id();
        DB::beginTransaction();
        try {
            $shoppingPlanCompany = $this->planCompanyRepository->create($data);

            if (!empty($data['monitor_ids'])) {
                $insertMonitors = resolve(MonitorService::class)->insertMonitors(
                    $data['monitor_ids'],
                    $shoppingPlanCompany->id,
                    Monitor::TYPE_SHOPPING_PLAN_COMPANY[$data['type']]
                );

                if (!$insertMonitors) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2025,
                    ];
                }
            }

            // Chi voi ke hoach tuan thi tao ra ke hoach don vi luon, con kh nam va quy thi khi gui thong bao dang ky moi tao
            if (ShoppingPlanCompany::TYPE_WEEK === $data['type']) {
                $insertShoppingPlanOrganizations = resolve(ShoppingPlanOrganizationService::class)->insertShoppingPlanOrganizations(
                    $shoppingPlanCompany->id,
                    $data['organization_ids'],
                    ShoppingPlanOrganization::STATUS_NEW
                );

                if (!$insertShoppingPlanOrganizations) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2057,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function updateShoppingPlanCompany($data, $id)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($id);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (!in_array($shoppingPlanCompany->status, [ShoppingPlanCompany::STATUS_NEW, ShoppingPlanCompany::STATUS_REGISTER])) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2059,
            ];
        }

        if (ShoppingPlanCompany::STATUS_NEW === (int) $shoppingPlanCompany->status) {
            $data['name'] = $this->getNameShoppingPlanCompany($data);
        }

        $data['updated_by'] = Auth::id();
        try {
            $this->planCompanyRepository->update($shoppingPlanCompany, $data);
            $monitorIds     =  $data['monitor_ids'] ?? [];
            $updateMonitors = resolve(MonitorService::class)->updateMonitor($id, $monitorIds, Monitor::TYPE_SHOPPING_PLAN_COMPANY[$data['type']]);
            if (!$updateMonitors) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2032,
                ];
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function deleteShoppingPlanCompany($id)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($id);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (ShoppingPlanCompany::STATUS_NEW != $shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2060,
            ];
        }

        DB::beginTransaction();
        try {
            $this->monitorRepository->deleteMonitor([
                'target_id' => $id,
                'type'      => Monitor::TYPE_SHOPPING_PLAN_COMPANY[$shoppingPlanCompany->type],
            ]);


            $delete = $shoppingPlanCompany->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => Auth::id(),
            ]);
            if (!$delete) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2061,
                ];
            }

            //            $this->shoppingPlanOrganizationRepository->deleteShoppingPlanOrganization([
            //                'shopping_plan_company_id' => $id,
            //            ]);

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function checkExistShoppingPlanCompany($data)
    {
        $shoppingPlanCompany = match ($data['type']) {
            ShoppingPlanCompany::TYPE_YEAR => $this->planCompanyRepository->getFirst([
                'time' => $data['time'],
                'type' => ShoppingPlanCompany::TYPE_YEAR,
            ]),
            ShoppingPlanCompany::TYPE_QUARTER => $this->planCompanyRepository->getFirst([
                'time'         => $data['time'],
                'type'         => ShoppingPlanCompany::TYPE_QUARTER,
                'plan_year_id' => $data['plan_year_id'],
            ]),
            ShoppingPlanCompany::TYPE_WEEK => $this->planCompanyRepository->getFirst([
                'time'            => $data['time'],
                'type'            => ShoppingPlanCompany::TYPE_WEEK,
                'month'           => $data['month'],
                'plan_year_id'    => $data['plan_year_id'],
                'plan_quarter_id' => $data['plan_quarter_id'],
            ]),
            default => [],
        };

        if (!empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2055,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getNameShoppingPlanCompany($data)
    {
        switch ($data['type']) {
            case ShoppingPlanCompany::TYPE_YEAR:
                $name = __('asset.shopping_plan_company.year.name', ['time' => $data['time']]);
                break;
            case ShoppingPlanCompany::TYPE_QUARTER:
                $shoppingPlanCompanyYear = $this->planCompanyRepository->find($data['plan_year_id']);
                $name                    = __('asset.shopping_plan_company.quarter.name', [
                    'time' => $data['time'],
                    'year' => $shoppingPlanCompanyYear->time,
                ]);
                break;
            case ShoppingPlanCompany::TYPE_WEEK:
                $shoppingPlanCompanyYear = $this->planCompanyRepository->find($data['plan_year_id']);
                $name                    = __('asset.shopping_plan_company.week.name', [
                    'time'  => $data['time'],
                    'month' => $data['month'],
                    'year'  => $shoppingPlanCompanyYear->time,
                ]);
                break;
            default:
                $name = '';
        }

        return $name;
    }

    public function findShoppingPlanCompany($id)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($id);

        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        $data                = $shoppingPlanCompany->toArray();
        $data['monitor_ids'] = ShoppingPlanCompany::TYPE_YEAR === +$shoppingPlanCompany->type ? $shoppingPlanCompany->monitorShoppingPlanYear?->pluck('user_id')->toArray() :
            $shoppingPlanCompany->monitorShoppingPlanQuarter?->pluck('user_id')->toArray();

        return [
            'success' => true,
            'data'    => $data,
        ];
    }

    public function getOrganizationRegisterYear($id)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($id);

        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        return [
            'success' => true,
            'data'    => OrganizationRegisterYearResource::make($shoppingPlanCompany)->resolve(),
        ];
    }

    public function deleteShoppingPlanCompanyMultiple(array $ids)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->getListing([
            'id'     => $ids,
            'status' => [
                ShoppingPlanCompany::STATUS_REGISTER,
                ShoppingPlanCompany::STATUS_PENDING_ACCOUNTANT_APPROVAL,
                ShoppingPlanCompany::STATUS_PENDING_MANAGER_APPROVAL,
                ShoppingPlanCompany::STATUS_DISAPPROVAL,
            ],
        ]);

        if ($shoppingPlanCompany->isNotEmpty()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2060,
            ];
        }

        $this->planCompanyRepository->deleteShoppingPlanCompanyByIds($ids);

        return [
            'success' => true,
        ];
    }

    public function getShoppingPlanCompanyOfMonitor(array $filters, $userId = null)
    {
        if (is_null($userId)) {
            $userId = Auth::id();
        }
        $monitors = $this->monitorRepository->getListing([
            'type'    => Monitor::TYPE_SHOPPING_PLAN_COMPANY[$filters['type']],
            'user_id' => $userId,
        ]);

        if ($monitors->isEmpty()) {
            return collect();
        }

        $planCompanyIds = $monitors->pluck('target_id')->toArray();
        $filters['id']  = $planCompanyIds;

        return $this->planCompanyRepository->getListing($filters);
    }

    public function sentNotificationRegister($shoppingPlanCompanyId)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($shoppingPlanCompanyId);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (ShoppingPlanCompany::STATUS_NEW !== +$shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2063,
            ];
        }

        DB::beginTransaction();
        try {
            $shoppingPlanCompany->status = ShoppingPlanCompany::STATUS_REGISTER;
            if (!$shoppingPlanCompany->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2062,
                ];
            }

            if (in_array($shoppingPlanCompany->type, [ShoppingPlanCompany::TYPE_YEAR, ShoppingPlanCompany::TYPE_QUARTER])) {
                $insertShoppingPlanOrganizations = resolve(ShoppingPlanOrganizationService::class)->insertShoppingPlanOrganizations(
                    $shoppingPlanCompany->id
                );

                if (!$insertShoppingPlanOrganizations) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2057,
                    ];
                }
            }
            DB::commit();
        } catch (\Throwable $exception) {
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
