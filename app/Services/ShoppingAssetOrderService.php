<?php

namespace App\Services;

use App\Repositories\ShoppingAssetOrderRepository;

class ShoppingAssetOrderService
{
    public function __construct(
        protected ShoppingAssetOrderRepository $shoppingAssetOrderRepository,
    ) {

    }

    public function getListShoppingAssetOrder($filters)
    {
        $result = $this->shoppingAssetOrderRepository->getListing($filters);

        return $result->toArray();
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
