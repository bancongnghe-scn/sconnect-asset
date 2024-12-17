<?php

namespace App\Services;

use App\Repositories\ShoppingAssetOrderRepository;

class ShoppingAssetOrderService
{
    public function __construct(
        protected ShoppingAssetOrderRepository $shoppingAssetOrderRepository,
    ) {

    }

    public function getList($filters)
    {
        $result = $this->shoppingAssetOrderRepository->getListing($filters);

        return $result->toArray();
    }
}
