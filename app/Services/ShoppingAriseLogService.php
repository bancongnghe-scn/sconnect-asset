<?php

namespace App\Services;

use App\Repositories\ShoppingAriseLogRepository;

class ShoppingAriseLogService
{
    public function __construct(
        protected ShoppingAriseLogRepository $shoppingAriseLogRepository,
    ) {
    }
}
