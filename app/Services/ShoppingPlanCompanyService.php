<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanCompanyResource;
use App\Models\Monitor;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\UserRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\DB;

class ShoppingPlanCompanyService
{
    public function __construct(
        protected ShoppingPlanCompanyRepository $planCompanyRepository,
        protected UserRepository $userRepository,
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
        $users = $this->userRepository->getListing(['id' => $userIds]);

        return ListShoppingPlanCompanyResource::make($planCompany)
            ->additional([
                'users' => $users->keyBy('id')
            ])
            ->resolve();
    }

    public function createShoppingPlanCompanyYear(array $data)
    {
         $shoppingPlanCompany = $this->planCompanyRepository->getFirst([
             'time' => $data['time'],
         ]);

         if (!empty($shoppingPlanCompany)) {
             return [
                 'success' => false,
                 'error_code' => AppErrorCode::CODE_2055,
                 'extra_data' => [
                     'year' => $data['time']
                 ]
             ];
         }

         $data['name'] = __('asset.shopping_plan_company.name', ['year' => $data['time']]);
         DB::beginTransaction();
        try {
            $shoppingPlanCompany = $this->planCompanyRepository->create($data);

            if (!empty($data['monitor_ids'])) {
                $insertMonitors = resolve(MonitorService::class)->insertMonitors(
                    $data['monitor_ids'], $shoppingPlanCompany->id,
                    Monitor::TYPE_SHOPPING_PLAN_COMPANY_YEAR
                );

                if (!$insertMonitors) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'error_code' => AppErrorCode::CODE_2056,
                    ];
                }
            }

            $insertShoppingPlanOrganizations = resolve(ShoppingPlanOrganizationService::class)->insertShoppingPlanOrganizations(
                $shoppingPlanCompany->id
            );


        } catch (\Throwable $exception) {
            DB::rollBack();
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }
}
