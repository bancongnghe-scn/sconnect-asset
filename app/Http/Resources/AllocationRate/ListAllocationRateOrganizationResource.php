<?php

namespace App\Http\Resources\AllocationRate;

use Illuminate\Http\Resources\Json\JsonResource;

class ListAllocationRateOrganizationResource extends JsonResource
{
    public function toArray($request)
    {
        $assetTypes    = $this->additional['assetTypes'] ?? [];
        $organizations = $this->additional['organizations'] ?? [];

        $data = [];
        foreach ($this->resource as $allocationRate) {
            if (empty($data[$allocationRate->organization_id])) {
                $data[$allocationRate->organization_id] = [
                    'organization_id'   => $allocationRate->organization_id,
                    'organization_name' => $organizations[$allocationRate->organization_id]['name'] ?? null,
                    'configs'           => [],
                ];
            }

            $data[$allocationRate->organization_id]['configs'][] = [
                'asset_type_id'   => $allocationRate->asset_type_id,
                'id'              => $allocationRate->id,
                'asset_type_name' => $assetTypes[$allocationRate->asset_type_id]['name'] ?? null,
                'level'           => $allocationRate->level,
                'price'           => $allocationRate->price,
                'description'     => $allocationRate->description,
            ];
        }

        return $data;
    }
}
