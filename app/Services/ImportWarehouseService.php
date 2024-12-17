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

    public function getAssetForImportWarehouse($orderId)
    {
        $data = $this->importWarehouseAssetRepository->getListing(['order_id' => $orderId]);
        if ($data->isEmpty()) {
            $data = $this->shoppingAssetOrderRepository->getListing(['order_id' => $orderId]);
        }

        return ListImportWarehouseAssetResource::make($data)->resolve();
    }
}
