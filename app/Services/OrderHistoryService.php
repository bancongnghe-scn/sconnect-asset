<?php

namespace App\Services;

use App\Repositories\OrderHistoryRepository;

class OrderHistoryService
{
    public function __construct(
        protected OrderHistoryRepository $orderHistoryRepository,
    ) {

    }
}
