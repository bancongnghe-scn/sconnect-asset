<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Repositories\JobTitleRepository;

class SyntheticOrganizationRegisterPlanWeekResource extends JsonResource
{
    protected $jobTitleRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->jobTitleRepository = new JobTitleRepository();
    }

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
                $jobTitle        = $this->jobTitleRepository->getJobs(['id' => $shoppingAsset->job_id]);
                $assetRegister[] = [
                    'id'                       => $shoppingAsset->id,
                    'asset_type_name'          => $shoppingAsset->assetType?->name,
                    'job_name'                 => !is_null($shoppingAsset->job_id) ? $jobTitle->first()['name'] : null,
                    'quantity_registered'      => $shoppingAsset->quantity_registered,
                    'quantity_approved'        => $shoppingAsset->quantity_approved,
                    'receiving_time'           => $shoppingAsset->receiving_time,
                    'description'              => $shoppingAsset->description,
                    'action'                   => $shoppingAsset->action,
                    'status'                   => $shoppingAsset->status,
                    'price'                    => $shoppingAsset->price,
                    'tax_money'                => $shoppingAsset->tax_money,
                    'supplier_id'              => $shoppingAsset->supplier_id,
                    'link'                     => $shoppingAsset->link,
                    'reason'                   => $shoppingAsset->reason,
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
