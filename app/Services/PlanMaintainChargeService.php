<?php

namespace App\Services;

use App\Models\PlanMaintainCharge;
use App\Repositories\PlanMaintainChargeRepository;
use App\Support\Constants\AppErrorCode;

class PlanMaintainChargeService
{
    public function __construct(
        protected PlanMaintainChargeRepository $planMaintainChargeRepository,
    ) {

    }

    public function updatePlanMaintainCharge($userNewIds, $planMaintainId)
    {
        $userOldIds = PlanMaintainCharge::where('plan_maintain_id', $planMaintainId)->pluck('user_id')->toArray();
        $userAddIds = array_diff($userNewIds, $userOldIds);
        if (!empty($userAddIds)) {
            // gan nha nguoi phu trach cho ke hoach
            $dataInsert = [];
            foreach ($userAddIds as $userId) {
                $dataInsert[] = [
                    'plan_maintain_id' => $planMaintainId,
                    'user_id'          => $userId,
                ];
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainChargeRepository->insert($dataInsert);
                if (!$insert) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2098,
                    ];
                }
            }
        }

        $userRemoveIds = array_diff($userOldIds, $userNewIds);
        if (!empty($userRemoveIds)) {
            PlanMaintainCharge::where('plan_maintain_id', $planMaintainId)->whereIn('user_id', $userRemoveIds)->delete();
        }

        return ['success' => true];
    }

    public function insertPlanMaintainCharge(array $useIds, $planMaintainId)
    {
        $dataInsert = [];
        foreach ($useIds as $userId) {
            $dataInsert[] = [
                'plan_maintain_id' => $planMaintainId,
                'user_id'          => $userId,
            ];
        }
        if (!empty($dataInsert)) {
            $insert = $this->planMaintainChargeRepository->insert($dataInsert);
            if (!$insert) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2098,
                ];
            }
        }

        return [
            'success' => true,
        ];
    }
}
