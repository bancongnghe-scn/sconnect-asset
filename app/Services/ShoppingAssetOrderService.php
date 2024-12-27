<?php

namespace App\Services;

use App\Http\Resources\ListShoppingAssetOrderResource;
use App\Repositories\AssetTypeRepository;
use App\Repositories\ShoppingAssetOrderRepository;
use Modules\Service\Repositories\OrganizationRepository;

class ShoppingAssetOrderService
{
    public function __construct(
        protected ShoppingAssetOrderRepository $shoppingAssetOrderRepository,
        protected OrganizationRepository $organizationRepository,
        protected AssetTypeRepository $assetTypeRepository,
    ) {

    }

    public function getListShoppingAssetOrder($filters)
    {
        $result = $this->shoppingAssetOrderRepository->getListing($filters);
        if ($result->isEmpty()) {
            return [];
        }

        $assetTypeIds = $result->pluck('asset_type_id')->toArray();
        $assetTypes   = [];
        if (!empty($assetTypeIds)) {
            $assetTypes = $this->assetTypeRepository->getListAssetType(['id' => $assetTypeIds])->keyBy('id')->toArray();
        }

        $organizationIds = $result->pluck('organization_id')->toArray();
        $organizations   = [];
        if (!empty($organizationIds)) {
            $organizations = $this->organizationRepository->getInfoOrganizationByFilters(['id' => $organizationIds])->keyBy('id')->toArray();
        }

        return ListShoppingAssetOrderResource::make($result)
            ->additional([
                'asset_types'   => $assetTypes,
                'organizations' => $organizations,
            ])
            ->resolve();
    }

    public function insertShoppingAssetOrder(array $data, $orderId)
    {
        $dataInsert = [];
        foreach ($data as $value) {
            $value['order_id'] = $orderId;
            $dataInsert[]      = $value;
        }

        if (!empty($dataInsert)) {
            return $this->shoppingAssetOrderRepository->insert($dataInsert);
        }

        return true;
    }
}
