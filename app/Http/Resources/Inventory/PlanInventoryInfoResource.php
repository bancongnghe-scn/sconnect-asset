<?php

namespace App\Http\Resources\Inventory;

use App\Models\Asset;
use App\Models\PlanMaintain;
use App\Repositories\AssetRepository;
use App\Repositories\PlanInventoryAssetRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Repositories\OrganizationRepository;

class PlanInventoryInfoResource extends JsonResource
{
    protected $assetRepository;
    protected $planInventoryAssetRepository;
    protected $organizationRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->assetRepository                     = new AssetRepository();
        $this->planInventoryAssetRepository        = new PlanInventoryAssetRepository();
        $this->organizationRepository              = new OrganizationRepository();
    }

    public function toArray($request)
    {
        $data                     = $this->resource->toArray();
        $data['user_ids']         = $this->resource->planMaintainCharge->pluck('user_id')->toArray();
        $data['asset_type_ids']   = $this->resource->planMaintainAssetTypes->pluck('asset_type_id')->toArray();
        $data['organization_ids'] = $this->resource->planMaintainOrganizations->pluck('organization_id')->toArray();
        $listOrganization         = $this->organizationRepository->getListing(['id' => $data['organization_ids']])->keyBy('id');
        if (PlanMaintain::STATUS_NEW == $this->resource->status) {
            $listAsset = $this->assetRepository->getListing([
                'organization_id' => $data['organization_ids'],
                'asset_type_id'   => $data['asset_type_ids'],
            ]);
        } else {
            $listAsset = $this->planInventoryAssetRepository->getListing([
                'plan_maintain_id' => $this->resource->id,
            ], with: ['asset']);
            foreach ($listAsset as &$asset) {
                if (!$asset->user_id && $asset->organization_id && Asset::STATUS_PENDING != $asset->status) {
                    $asset->manager_id = $listOrganization[$asset->organization_id]['manager_id'] ?? null;
                }
            }
        }
        $data['assets'] = $listAsset;

        return $data;
    }
}
