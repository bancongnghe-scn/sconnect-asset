<?php

namespace App\Http\Resources\AllocationRate;

use Illuminate\Http\Resources\Json\JsonResource;

class ListAllocationRatePositionResource extends JsonResource
{
    public function toArray($request)
    {
        $positions     = $this->additional['positions'] ?? [];
        $assetTypes    = $this->additional['assetTypes'] ?? [];
        $organizations = $this->additional['organizations'] ?? [];

        $data = [];
        foreach ($this->resource as $allocationRatePosition) {
            if (empty($data[$allocationRatePosition->organization_id . '_' . $allocationRatePosition->position_id])) {
                $data[$allocationRatePosition->organization_id . '_' . $allocationRatePosition->position_id] = [
                    'organization_id'   => $allocationRatePosition->organization_id,
                    'position_id'       => $allocationRatePosition->position_id,
                    'organization_name' => $organizations[$allocationRatePosition->organization_id]['name'] ?? null,
                    'position_name'     => $positions[$allocationRatePosition->position_id]['name'] ?? null,
                    'configs'           => [],
                ];
            }

            $data[$allocationRatePosition->organization_id . '_' . $allocationRatePosition->position_id]['configs'][] = [
                'id'              => $allocationRatePosition->id,
                'asset_type_id'   => $allocationRatePosition->asset_type_id,
                'asset_type_name' => $assetTypes[$allocationRatePosition->asset_type_id]['name'] ?? null,
                'level'           => $allocationRatePosition->level,
                'price'           => $allocationRatePosition->price,
                'description'     => $allocationRatePosition->description,
            ];
        }

        return $data;
    }
}
