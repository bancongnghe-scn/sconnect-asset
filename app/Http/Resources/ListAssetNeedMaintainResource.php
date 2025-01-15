<?php

namespace App\Http\Resources;

use App\Repositories\AssetTypeRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Services\OrganizationService;

class ListAssetNeedMaintainResource extends JsonResource
{
    protected $assetTypeRepository;
    protected $userRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->assetTypeRepository    = new AssetTypeRepository();
        $this->userRepository         = new UserRepository();
    }

    public function toArray($request)
    {
        $assetTypeIds = $this->resource->pluck('asset_type_id')->toArray();
        $assetTypes   = [];
        if (!empty($assetTypeIds)) {
            $assetTypes = $this->assetTypeRepository->getListAssetType(['id' => $assetTypeIds])->keyBy('id')->toArray();
        }

        $userIds = $this->resource->pluck('user_id')->toArray();
        $users   = [];
        if (!empty($userIds)) {
            $users = $this->userRepository->getListing(['id' => $userIds])->keyBy('id')->toArray();
        }

        $organizationIds = $this->resource->pluck('organization_id')->toArray();
        $organizations   = [];
        if (!empty($organizationIds)) {
            $organizations = resolve(OrganizationService::class)->getOrganizationalStructure($organizationIds);
        }

        $data = [];
        foreach ($this->resource as $asset) {
            $data[] = [
                'id'                      => $asset->id,
                'asset_id'                => $asset->id,
                'name'                    => $asset->name,
                'code'                    => $asset->code,
                'asset_type_name'         => $assetTypes[$asset->asset_type_id]['name'] ?? '',
                'serial_number'           => $asset->serial_number,
                'user_id'                 => $asset->user_id,
                'user'                    => $users[$asset->user_id] ?? [],
                'organization_id'         => $asset->organization_id,
                'organization'            => $organizations[$asset->organization_id] ?? [],
                'recent_maintenance_date' => $asset->recent_maintenance_date,
                'next_maintenance_date'   => $asset->next_maintenance_date,
                'location'                => $asset->location,
                'status'                  => $asset->status,
            ];
        }

        $assetNeedMaintain         = $this->resource->toArray();
        if (!empty($assetNeedMaintain['total'])) {
            $assetNeedMaintain['data'] = $data;

            return $assetNeedMaintain;
        }

        return $data;
    }
}
