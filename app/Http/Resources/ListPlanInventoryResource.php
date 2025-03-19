<?php

namespace App\Http\Resources;

use App\Models\PlanInventoryAsset;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPlanInventoryResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $planInventory) {
            $assetInventoried    = $planInventory->planInventoryAsset->where('status', PlanInventoryAsset::STATUS_INVENTORIED)->count();
            $totalAssetInventory = $planInventory->planInventoryAsset->count();
            $process             = $assetInventoried > 0 ? round($assetInventoried / $totalAssetInventory, 2) * 100 : 0;
            $data[]              = [
                'id'            => $planInventory->id,
                'name'          => $planInventory->name,
                'start_time'    => $planInventory->start_time,
                'end_time'      => $planInventory->end_time,
                'organizations' => $planInventory->planMaintainOrganizations?->pluck('organization.name')->toArray(),
                'asset_types'   => $planInventory->planMaintainAssetTypes?->pluck('assetType.name')->toArray(),
                'status'        => $planInventory->status,
                'process'       => $process,
            ];
        }

        $listPlanInventory = $this->resource->toArray();
        if (!empty($listPlanInventory['total'])) {
            $listPlanInventory['data'] = $data;

            return $listPlanInventory;
        }

        return $data;
    }
}
