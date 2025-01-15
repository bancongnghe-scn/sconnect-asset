<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Services\OrganizationService;

class InfoPlanMaintainResource extends JsonResource
{
    protected $userRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->userRepository         = new UserRepository();
    }

    public function toArray($request)
    {
        $data = [
            'id'                => $this->resource->id,
            'name'              => $this->resource->name,
            'code'              => $this->resource->code,
            'note'              => $this->resource->note,
            'status'            => $this->resource->status,
            'start_time'        => $this->resource->start_time,
            'end_time'          => $this->resource->end_time,
            'maintain_costs'    => $this->resource->maintain_costs,
            'sent_notification' => $this->resource->sent_notification,
            'organization_ids'  => $this->resource?->planMaintainOrganizations->pluck('organization_id')->toArray(),
            'supplier_ids'      => $this->resource?->planMaintainSuppliers->pluck('supplier_id')->toArray(),
            'user_ids'          => $this->resource?->planMaintainCharge->pluck('user_id')->toArray(),
        ];

        $assets          = $this->resource?->planMaintainAsset;
        $organizationIds = $assets->pluck('organization_id')->toArray();
        $organizations   = [];
        if (!empty($organizationIds)) {
            $organizations = resolve(OrganizationService::class)->getOrganizationalStructure($organizationIds);
        }
        $userIds = $assets->pluck('user_id')->toArray();
        $users   = [];
        if (!empty($userIds)) {
            $users = $this->userRepository->getListing(['id' => $userIds])->keyBy('id')->toArray();
        }

        foreach ($assets as $asset) {
            $asset->organization = $organizations[$asset->organization_id] ?? [];
            $asset->user         = $users[$asset->user_id] ?? [];
        }

        $data['assets_maintain'] = $assets;

        return $data;
    }
}
