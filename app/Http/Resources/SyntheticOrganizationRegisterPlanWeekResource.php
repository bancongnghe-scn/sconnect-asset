<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SyntheticOrganizationRegisterPlanWeekResource extends JsonResource
{
    public function toArray($request)
    {
        $shoppingPlanCompany = $this->resource;
        $organizations       = $this->additional['organizations'] ?? [];

        $data = [
            'organizations'  => [],
            'total_register' => 0,
        ];
        foreach ($shoppingPlanCompany->shoppingPlanOrganizations as $shoppingPlanOrganization) {
            $assetRegister = [];
            foreach ($shoppingPlanOrganization->shoppingAssets as $shoppingAsset) {
                $assetRegister[] = [
                    'id'                  => $shoppingAsset->id,
                    'asset_type_name'     => $shoppingAsset->assetType?->name,
                    'job_id'              => $shoppingAsset->job_id,
                    'quantity_registered' => $shoppingAsset->quantity_registered,
                    'quantity_approved'   => $shoppingAsset->quantity_approved,
                    'receiving_time'      => $shoppingAsset->receiving_time,
                    'description'         => $shoppingAsset->description,
                ];
                $data['total_register'] += $shoppingAsset->quantity_registered;
            }

            $data['organizations'][] = [
                'id'             => $shoppingPlanOrganization->id,
                'name'           => $organizations[$shoppingPlanOrganization->organization_id]['name'] ?? '',
                'status'         => $shoppingPlanOrganization->status,
                'note'           => $shoppingPlanOrganization->note,
                'asset_register' => empty($assetRegister) ? [[]] : $assetRegister,
            ];
        }

        return $data;
    }
}
