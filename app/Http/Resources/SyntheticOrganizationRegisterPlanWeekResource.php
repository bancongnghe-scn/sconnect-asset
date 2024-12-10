<?php

namespace App\Http\Resources;

use App\Services\ScApiService;
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
                    'job_name'            => !is_null($shoppingAsset->job_id) ? ScApiService::getJobByIds($shoppingAsset->job_id)->first()['name'] : null,
                    'quantity_registered' => $shoppingAsset->quantity_registered,
                    'quantity_approved'   => $shoppingAsset->quantity_approved,
                    'receiving_time'      => $shoppingAsset->receiving_time,
                    'description'         => $shoppingAsset->description,
                    'action'              => $shoppingAsset->action,
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
