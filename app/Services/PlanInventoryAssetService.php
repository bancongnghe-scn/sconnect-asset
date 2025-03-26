<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\PlanInventoryAsset;
use App\Repositories\PlanInventoryAssetRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;

class PlanInventoryAssetService
{
    public function __construct(
        protected PlanInventoryAssetRepository $planInventoryAssetRepository,
    ) {
    }

    public function generalPlanInventoryAsset($assets, $planInventoryId)
    {
        $data   = [];
        $userId = Auth::id();
        foreach ($assets as $asset) {
            $managerId = $asset->organization_id && !$asset->user_id && Asset::STATUS_PENDING != $asset->status
                ? $asset->organization?->manager_id : null;
            $data[] = [
                'plan_maintain_id'             => $planInventoryId,
                'asset_id'                     => $asset->id,
                'status'                       => PlanInventoryAsset::STATUS_NOT_INVENTORIED,
                'organization_id'              => $asset->organization_id,
                'user_id'                      => $asset->user_id,
                'manager_id'                   => $managerId,
                'status_asset'                 => $asset->status,
                'location'                     => $asset->location,
                'organization_id_present'      => $asset->organization_id,
                'user_id_present'              => $asset->user_id,
                'manager_id_present'           => $managerId,
                'status_asset_present'         => $asset->status,
                'location_present'             => $asset->location,
                'total_present'                => 1,
                'config_info'                  => $asset->config_info,
                'config_info_present'          => $asset->config_info,
                'created_by'                   => $userId,
            ];
        }

        if (!empty($data)) {
            $insert = $this->planInventoryAssetRepository->insert($data);
            if (!$insert) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2115,
                ];
            }
        }

        return [
            'success' => true,
        ];
    }
}
