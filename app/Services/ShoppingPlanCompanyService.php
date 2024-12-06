<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Http\Resources\SyntheticOrganizationRegisterPlanResource;
use App\Models\Monitor;
use App\Models\ShoppingPlanCompany;
use App\Models\ShoppingPlanLog;
use App\Models\ShoppingPlanOrganization;
use App\Repositories\MonitorRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanLogRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use App\Repositories\UserRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShoppingPlanCompanyService
{
    public function __construct(
        protected ShoppingPlanCompanyRepository $planCompanyRepository,
        protected UserRepository $userRepository,
        protected MonitorRepository $monitorRepository,
        protected ShoppingPlanOrganizationRepository $shoppingPlanOrganizationRepository,
        protected ShoppingPlanLogRepository $shoppingPlanLogRepository,
    ) {

    }

    public function getListShoppingPlanCompany(array $filters)
    {
        $user = Auth::user();
        if ($user->can('shopping_plan_company.view_all')) {
            $planCompany = $this->planCompanyRepository->getListing($filters, [
                'id', 'name', 'time',
                'start_time', 'end_time', 'plan_year_id',
                'status', 'created_by', 'created_at',
            ]);
        } else {
            $planCompany = $this->getShoppingPlanCompanyOfMonitor($filters, $user->id);
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

            // Tao log
            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_CREATE_SHOPPING_PLAN_COMPANY,
                $shoppingPlanCompany->id
            );
            if (!$insertLog) {
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
            $data['id'] = $shoppingPlanCompany->id;
            $checkExist = $this->checkExistShoppingPlanCompany($data, 'update');
            if (!$checkExist['success']) {
                return $checkExist;
            }

            $data['name'] = $this->getNameShoppingPlanCompany($data);
        }

        $data['updated_by'] = Auth::id();
        try {
            $this->planCompanyRepository->update($shoppingPlanCompany, $data);
            $monitorIds     = $data['monitor_ids'] ?? [];
            $updateMonitors = resolve(MonitorService::class)->updateMonitor($id, $monitorIds, Monitor::TYPE_SHOPPING_PLAN_COMPANY[$data['type']]);
            if (!$updateMonitors) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2032,
                ];
            }

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_UPDATE_SHOPPING_PLAN_COMPANY,
                $id
            );
            if (!$insertLog) {
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

    public function checkExistShoppingPlanCompany($data, $action = 'create')
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
                'plan_quarter_id' => $data['plan_quarter_id'],
            ]),
            default => [],
        };

        $check = 'create' === $action ? !empty($shoppingPlanCompany) : $shoppingPlanCompany->id !== $data['id'];

        if ($check) {
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
                $shoppingPlanCompanyQuarter = $this->planCompanyRepository->find($data['plan_quarter_id']);
                $name                       = __('asset.shopping_plan_company.week.name', [
                    'time'  => $data['time'],
                    'month' => $data['month'],
                    'year'  => $shoppingPlanCompanyQuarter?->shoppingPlanCompanyYear->time,
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
        switch ($shoppingPlanCompany->type) {
            case ShoppingPlanCompany::TYPE_YEAR:
                $data['monitor_ids'] =  $shoppingPlanCompany->monitorShoppingPlanYear?->pluck('user_id')->toArray();
                break;
            case ShoppingPlanCompany::TYPE_QUARTER:
                $data['monitor_ids'] = $shoppingPlanCompany->monitorShoppingPlanQuarter?->pluck('user_id')->toArray();
                break;
            case ShoppingPlanCompany::TYPE_WEEK:
                $data['monitor_ids'] = $shoppingPlanCompany->monitorShoppingPlanWeek?->pluck('user_id')->toArray();
                break;
            default:
                $data['monitor_ids'] = [];
        }

        return [
            'success' => true,
            'data'    => $data,
        ];
    }

    public function getOrganizationRegister($id)
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
            'data'    => SyntheticOrganizationRegisterPlanResource::make($shoppingPlanCompany)->resolve(),
        ];
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

            if (ShoppingPlanCompany::TYPE_WEEK === $shoppingPlanCompany->type) {
                $this->shoppingPlanOrganizationRepository->deleteShoppingPlanOrganization([
                    'shopping_plan_company_id' => $id,
                ]);
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

    public function deleteShoppingPlanCompanyMultiple(array $ids, int $type)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->getFirst([
            'ids'     => $ids,
            'status'  => [
                ShoppingPlanCompany::STATUS_REGISTER,
                ShoppingPlanCompany::STATUS_PENDING_ACCOUNTANT_APPROVAL,
                ShoppingPlanCompany::STATUS_PENDING_MANAGER_APPROVAL,
                ShoppingPlanCompany::STATUS_APPROVAL,
                ShoppingPlanCompany::STATUS_DISAPPROVAL,
            ],
        ]);

        if (!empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2060,
            ];
        }

        DB::beginTransaction();
        try {
            $this->planCompanyRepository->deleteShoppingPlanCompanyByIds($ids);
            $this->monitorRepository->deleteMonitor([
                'target_id' => $ids,
                'type'      => Monitor::TYPE_SHOPPING_PLAN_COMPANY[$type],
            ]);

            if (ShoppingPlanCompany::TYPE_WEEK === $type) {
                $this->shoppingPlanOrganizationRepository->deleteShoppingPlanOrganization([
                    'shopping_plan_company_id' => $ids,
                ]);
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

    public function sentNotificationRegister(array $data)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($data['id']);
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

            $insertShoppingPlanOrganizations = resolve(ShoppingPlanOrganizationService::class)->insertShoppingPlanOrganizations(
                $shoppingPlanCompany->id,
                $data['organizations'] ?? []
            );

            if (!$insertShoppingPlanOrganizations) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2057,
                ];
            }

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_SENT_NOTIFICATION_SHOPPING_PLAN_COMPANY,
                $data['id']
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

    public function sendAccountantApproval($shoppingPlanCompanyId)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($shoppingPlanCompanyId);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (ShoppingPlanCompany::STATUS_REGISTER !== +$shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2064,
            ];
        }

        if (Carbon::now() < Carbon::parse($shoppingPlanCompany->end_time)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2065,
            ];
        }

        DB::beginTransaction();
        try {
            $shoppingPlanCompany->status = ShoppingPlanCompany::STATUS_PENDING_ACCOUNTANT_APPROVAL;
            if (!$shoppingPlanCompany->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2066,
                ];
            }

            $this->shoppingPlanOrganizationRepository->updateShoppingPlanOrganization([
                'shopping_plan_company_id' => $shoppingPlanCompanyId,
            ], [
                'status' => ShoppingPlanOrganization::STATUS_PENDING_ACCOUNTANT_APPROVAL,
            ]);

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_SEND_ACCOUNTANT_APPROVAL_SHOPPING_PLAN_COMPANY,
                $shoppingPlanCompanyId
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

    public function sendManagerApproval($shoppingPlanCompanyId)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($shoppingPlanCompanyId);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (ShoppingPlanCompany::STATUS_PENDING_ACCOUNTANT_APPROVAL !== +$shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2064,
            ];
        }

        if (Carbon::now() < Carbon::parse($shoppingPlanCompany->end_time)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2065,
            ];
        }

        $shoppingPlanOrganization = $this->shoppingPlanOrganizationRepository->getFirst([
            'shopping_plan_company_id' => $shoppingPlanCompanyId,
            'status'                   => [
                ShoppingPlanOrganization::STATUS_PENDING_ACCOUNTANT_APPROVAL,
                ShoppingPlanOrganization::STATUS_ACCOUNTANT_REVIEWING,
            ],
        ]);
        if (!empty($shoppingPlanOrganization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2077,
            ];
        }

        DB::beginTransaction();
        try {
            $shoppingPlanCompany->status = ShoppingPlanCompany::STATUS_PENDING_MANAGER_APPROVAL;
            if (!$shoppingPlanCompany->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2066,
                ];
            }

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_SEND_MANAGER_APPROVAL_SHOPPING_PLAN_COMPANY,
                $shoppingPlanCompanyId
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

    public function managerApproval($data)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($data['id']);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (!in_array($shoppingPlanCompany->status, [
            ShoppingPlanCompany::STATUS_PENDING_MANAGER_APPROVAL,
            ShoppingPlanCompany::STATUS_APPROVAL,
            ShoppingPlanCompany::STATUS_DISAPPROVAL,
        ])) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2074,
            ];
        }

        $isApproval = ShoppingPlanCompany::TYPE_APPROVAL == $data['type'];
        if ($isApproval) {
            $shoppingPlanCompany->status = ShoppingPlanCompany::STATUS_APPROVAL;
            $action                      = ShoppingPlanLog::ACTION_MANAGER_APPROVAL_SHOPPING_PLAN_COMPANY;
        } else {
            $shoppingPlanCompany->status = ShoppingPlanCompany::STATUS_DISAPPROVAL;
            $shoppingPlanCompany->note   = $data['note'] ?? null;
            $action                      = ShoppingPlanLog::ACTION_MANAGER_DISAPPROVAL_SHOPPING_PLAN_COMPANY;
        }
        DB::beginTransaction();
        try {
            if (!$shoppingPlanCompany->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2062,
                ];
            }

            if ($isApproval) {
                $filters = [
                    'shopping_plan_company_id' => $data['id'],
                    'status'                   => [
                        ShoppingPlanOrganization::STATUS_PENDING_MANAGER_APPROVAL,
                        ShoppingPlanOrganization::STATUS_MANAGER_DISAPPROVAL,
                    ],
                ];
                $dataUpdate =  [
                    'status' => ShoppingPlanOrganization::STATUS_MANAGER_APPROVAL,
                    'note'   => $data['note'] ?? null,
                ];

            } else {
                $filters = [
                    'shopping_plan_company_id' => $data['id'],
                    'status'                   => [
                        ShoppingPlanOrganization::STATUS_PENDING_MANAGER_APPROVAL,
                        ShoppingPlanOrganization::STATUS_MANAGER_APPROVAL,
                    ],
                ];
                $dataUpdate =  [
                    'status' => ShoppingPlanOrganization::STATUS_MANAGER_DISAPPROVAL,
                    'note'   => $data['note'] ?? null,
                ];
            }

            $shoppingPlanOrganizations = $this->shoppingPlanOrganizationRepository->getListing($filters);

            $this->shoppingPlanOrganizationRepository->updateShoppingPlanOrganization($filters, $dataUpdate);

            $dataLogs = [];
            $desc     = $isApproval ? __('shopping_plan_log.' . $action) : __('shopping_plan_log.' . $action, [
                'note' => $data['note'] ?? null,
            ]);
            foreach ($shoppingPlanOrganizations as $shoppingPlanOrganization) {
                $dataLogs[] = [
                    'action'     => $action,
                    'record_id'  => $shoppingPlanOrganization->id,
                    'desc'       => $desc,
                    'created_by' => Auth::id(),
                ];
            }

            $dataLogs[] = [
                'action'     => $action,
                'record_id'  => $shoppingPlanCompany->id,
                'desc'       => $desc,
                'created_by' => Auth::id(),
            ];

            $insertLog = $this->shoppingPlanLogRepository->insert($dataLogs);
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

    public function getListShoppingPlanByFilters($filters)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->getListing($filters);
        if ($shoppingPlanCompany->isEmpty()) {
            return [];
        }

        return $shoppingPlanCompany->toArray();
    }
}
