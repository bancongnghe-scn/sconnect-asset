<?php

namespace App\Services;

use App\Repositories\PlanMaintainAssetTypeRepository;

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
}
