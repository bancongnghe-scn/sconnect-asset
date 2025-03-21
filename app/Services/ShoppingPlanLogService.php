<?php

namespace App\Services;

use App\Repositories\ShoppingPlanLogRepository;
use App\Repositories\UserRepository;

class ShoppingPlanLogService
{
    public function __construct(
        protected ShoppingPlanLogRepository $shoppingPlanLogRepository,
        protected UserRepository $userRepository,
    ) {

    }

    public function getShoppingPlanLogByRecordId($id)
    {
        $logs = $this->shoppingPlanLogRepository->getListing([
            'record_id' => $id,
        ]);

        return $logs->toArray();
    }
}
