<?php

namespace App\Services;

use App\Models\PlanMaintainAssetType;
use App\Repositories\PlanMaintainAssetTypeRepository;
use App\Support\Constants\AppErrorCode;

class PlanMaintainAssetTypeService
{
    public function __construct(
        protected PlanMaintainAssetTypeRepository $planMaintainAssetTypeRepository,
    ) {
    }

    public function insertPlanMaintainAssetType($planMaintainId, array $assetTypeIds)
    {
        $dataInsert = [];
        foreach ($assetTypeIds as $assetTypeId) {
            $dataInsert[] = [
                'plan_maintain_id' => $planMaintainId,
                'asset_type_id'    => $assetTypeId,
            ];
        }

        return $this->planMaintainAssetTypeRepository->insert($dataInsert);
    }

    public function updatePlanMaintainAssetType($assetTypeNewIds, $planId)
    {
        $assetTypeOldIds = $this->planMaintainAssetTypeRepository->getByPlanId($planId)->pluck('asset_type_id')->toArray();
        $assetTypeAddIds = array_diff($assetTypeNewIds, $assetTypeOldIds);
        if (!empty($assetTypeAddIds)) {
            $dataInsert = [];
            foreach ($assetTypeAddIds as $assetTypeId) {
                $dataInsert[] = [
                    'plan_maintain_id' => $planId,
                    'asset_type_id'    => $assetTypeId,
                ];
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainAssetTypeRepository->insert($dataInsert);
                if (!$insert) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2008,
                    ];
                }
            }
        }

        $assetTypeRemoveIds = array_diff($assetTypeOldIds, $assetTypeNewIds);
        if (!empty($assetTypeRemoveIds)) {
            PlanMaintainAssetType::where('plan_maintain_id', $planId)->whereIn('asset_type_id', $assetTypeRemoveIds)->delete();
        }

        return [
            'success'    => true,
        ];
    }
}
