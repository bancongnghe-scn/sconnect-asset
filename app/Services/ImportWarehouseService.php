<?php

namespace App\Services;

use App\Http\Resources\ListImportWarehouseAssetResource;
use App\Repositories\ImportWarehouse\ImportWarehouseAssetRepository;
use App\Repositories\ShoppingAssetOrderRepository;

class ImportWarehouseService
{
    public function __construct(
        protected ImportWarehouseAssetRepository $importWarehouseAssetRepository,
        protected ShoppingAssetOrderRepository $shoppingAssetOrderRepository,
    ) {

    }

    public function getAssetForImportWarehouse(array $orderIds)
    {
        if (empty($orderIds)) {
            return [];
        }

        $data                          = $this->importWarehouseAssetRepository->getListing(['order_id' => $orderIds]);
        $orderIdsImportWarehouseAssets = $data->pluck('order_id')->unique()->toArray();
        $orderIdsShoppingAssetOrder    = array_diff($orderIds, $orderIdsImportWarehouseAssets);
        if (!empty($orderIdsShoppingAssetOrder)) {
            $data = $data->merge($this->shoppingAssetOrderRepository->getListing(['order_id' => $orderIdsShoppingAssetOrder]));
        }

        return ListImportWarehouseAssetResource::make($data)->resolve();
    }
}
