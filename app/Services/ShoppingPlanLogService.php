<?php

namespace App\Services;

use App\Repositories\ShoppingPlanLogRepository;

class ShoppingPlanLogService
{
    public function __construct(
        protected ShoppingPlanLogRepository $shoppingPlanLogRepository,
    ) {

    }
}
