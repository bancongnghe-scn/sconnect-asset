<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ShoppingAssetRepository;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ShoppingAssetRepository $shoppingAssetRepository,
    ) {
    }

    public function getListOrder($filters)
    {
        $data = $this->orderRepository->getListing($filters);

        return $data->toArray();
    }
}
