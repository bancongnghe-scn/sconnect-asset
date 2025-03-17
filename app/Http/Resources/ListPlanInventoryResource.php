<?php

namespace App\Http\Resources;

use App\Models\PlanMaintainAsset;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPlanInventoryResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $planInventory) {
            $assetInventoried    = $planInventory->planMaintainAsset->where('status', PlanMaintainAsset::STATUS_COMPLETE_MAINTAINING)->count();
            $totalAssetInventory = $planInventory->planMaintainAsset->count();
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
