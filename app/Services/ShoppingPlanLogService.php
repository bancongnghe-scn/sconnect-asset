<?php

namespace App\Services;

use App\Http\Resources\ListShoppingPlanLogResource;
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

        if (empty($logs)) {
            return [];
        }

        $createdBys = $logs->pluck('created_by')->toArray();
        $users      = $this->userRepository->getListing([
            'id' => $createdBys,
        ])->keyBy('id');

        return ListShoppingPlanLogResource::make($logs)->additional([
            'users' => $users,
        ])->resolve();
    }
}
