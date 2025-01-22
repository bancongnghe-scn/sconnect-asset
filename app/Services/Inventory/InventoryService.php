<?php

namespace App\Services\Inventory;

use App\Http\Resources\Manage\PlanMaintainResource;
use App\Http\Resources\PlanInventoryResource;
use App\Models\PlanMaintain;
use App\Repositories\AssetTypeRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use Modules\Service\Repositories\OrganizationRepository;

class InventoryService
{
    public function __construct(
        protected PlanMaintainRepository $planMaintainRepository,
        protected OrganizationRepository $organizationRepository,
        protected AssetTypeRepository $assetTypeRepository,
    )
    {
    }

    public function getPlanInventory($filters)
    {
        $filters['type'] = PlanMaintain::TYPE_INVENTORY;
        $data = $this->planMaintainRepository->getListing($filters, with: [
            'planMaintainAsset',
            'planMaintainOrganizations' => ['organization'],
            'planMaintainAssetTypes' => ['assetType']
        ]);

        if (empty($data)) {
            return [];
        }

        return PlanInventoryResource::make($data)->resolve();
    }
}
