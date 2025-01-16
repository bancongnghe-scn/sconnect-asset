<?php

namespace App\Services;

use App\Models\PlanMaintainOrganization;
use App\Repositories\PlanMaintainOrganizationRepository;
use App\Support\Constants\AppErrorCode;

class PlanMaintainOrganizationService
{
    public function __construct(
        protected PlanMaintainOrganizationRepository $planMaintainOrganizationRepository,
    ) {

    }

    public function updatePlanMaintainOrganization($organizationNewIds, $planId)
    {
        $organizationOldIds = PlanMaintainOrganization::where('plan_maintain_id', $planId)->pluck('organization_id')->toArray();
        $organizationAddIds = array_diff($organizationNewIds, $organizationOldIds);
        if (!empty($organizationAddIds)) {
            $dataInsert = [];
            foreach ($organizationAddIds as $organizationId) {
                $dataInsert[] = [
                    'plan_maintain_id' => $planId,
                    'organization_id'  => $organizationId,
                ];
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainOrganizationRepository->insert($dataInsert);
                if (!$insert) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2096,
                    ];
                }
            }
        }

        $organizationRemoveIds = array_diff($organizationOldIds, $organizationAddIds);
        if (!empty($organizationRemoveIds)) {
            PlanMaintainOrganization::where('plan_maintain_id', $planId)->whereIn('organization_id', $organizationRemoveIds)->delete();
        }

        return [
            'success'    => true,
        ];
    }
}
