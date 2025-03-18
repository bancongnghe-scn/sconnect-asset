<?php

namespace App\Http\Resources\Inventory;

use App\Models\PlanMaintain;
use App\Repositories\AssetRepository;
use App\Repositories\PlanInventoryAssetOutsideRepository;
use App\Repositories\PlanInventoryAssetRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanInventoryInfoResource extends JsonResource
{
    protected $assetRepository;
    protected $planInventoryAssetRepository;
    protected $planInventoryAssetOutsideRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->assetRepository                     = new AssetRepository();
        $this->planInventoryAssetRepository        = new PlanInventoryAssetRepository();
        $this->planInventoryAssetOutsideRepository = new PlanInventoryAssetOutsideRepository();
    }

    public function toArray($request)
    {
        $data                     = $this->resource->toArray();
        $data['user_ids']         = $this->resource->planMaintainCharge->pluck('user_id')->toArray();
        $data['asset_type_ids']   = $this->resource->planMaintainAssetTypes->pluck('asset_type_id')->toArray();
        $data['organization_ids'] = $this->resource->planMaintainOrganizations->pluck('organization_id')->toArray();
        if (PlanMaintain::STATUS_NEW == $this->resource->status) {
            $listAsset = $this->assetRepository->getListing([
                'organization_id' => $data['organization_ids'],
                'asset_type_id'   => $data['asset_type_ids'],
            ]);
        } else {
            $listAsset = [
                'inventory' => $this->planInventoryAssetRepository->getListing([
                    'plan_maintain_id' => $this->resource->id,
                ]),
                'inventory_outside' => $this->planInventoryAssetOutsideRepository->getListing([
                    'plan_maintain_id' => $this->resource->id,
                ]),
            ];
        }
        $data['assets'] = $listAsset;

        return $data;
    }
}
