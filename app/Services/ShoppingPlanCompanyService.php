<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Models\Monitor;
use App\Models\ShoppingPlanCompany;
use App\Repositories\MonitorRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanOrganizationRepository;
use App\Repositories\UserRepository;
use App\Support\AppErrorCode;
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

    public function getListPlanCompany(array $filters)
    {
        $planCompany = $this->planCompanyRepository->getListing($filters, [
            'id', 'name', 'time',
            'start_time', 'end_time', 'plan_year_id',
            'status', 'created_by', 'created_at',
        ]);

        if (empty($planCompany)) {
            return [];
        }

        $userIds = $planCompany->pluck('created_by')->toArray();
        $users   = $this->userRepository->getListing(['id' => $userIds]);

        return ListShoppingPlanCompanyResource::make($planCompany)
            ->additional([
                'users' => $users->keyBy('id'),
            ])
            ->resolve();
    }

    public function createShoppingPlanCompanyYear(array $data)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->getFirst([
            'time' => $data['time'],
            'type' => $data['type'],
        ]);

        if (!empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2055,
                'extra_data' => [
                    'year' => $data['time'],
                ],
            ];
        }

        $data['name']   = __('asset.shopping_plan_company.year.name', ['year' => $data['time']]);
        $data['status'] = ShoppingPlanCompany::STATUS_REGISTER;
        DB::beginTransaction();
        try {
            $shoppingPlanCompany = $this->planCompanyRepository->create($data);

            if (!empty($data['monitor_ids'])) {
                $insertMonitors = resolve(MonitorService::class)->insertMonitors(
                    $data['monitor_ids'],
                    $shoppingPlanCompany->id,
                    Monitor::TYPE_SHOPPING_PLAN_COMPANY_YEAR
                );

                if (!$insertMonitors) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2025,
                    ];
                }
            }

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

    public function updateShoppingPlanCompanyYear($data, $id)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($id);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (ShoppingPlanCompany::STATUS_REGISTER != $shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2059,
            ];
        }

        $data['name']       = __('asset.shopping_plan_company.year.name', ['year' => $data['time']]);
        $data['updated_by'] = Auth::id();
        try {
            $this->planCompanyRepository->update($shoppingPlanCompany, $data);
            $monitorIds     =  $data['monitor_ids'] ?? [];
            $updateMonitors = resolve(MonitorService::class)->updateMonitor($id, $monitorIds, Monitor::TYPE_SHOPPING_PLAN_COMPANY_YEAR);
            if (!$updateMonitors) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2057,
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

    public function deleteShoppingPlanCompanyYear($id)
    {
        $shoppingPlanCompany = $this->planCompanyRepository->find($id);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }

        if (ShoppingPlanCompany::STATUS_REGISTER != $shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2060,
            ];
        }

        DB::beginTransaction();
        try {
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

            $this->monitorRepository->deleteMonitor([
                'target_id' => $id,
                'type'      => Monitor::TYPE_SHOPPING_PLAN_COMPANY_YEAR,
            ]);


            $this->shoppingPlanOrganizationRepository->deleteShoppingPlanOrganization([
                'shopping_plan_company_id' => $id,
            ]);

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
}
