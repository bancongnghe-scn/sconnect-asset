<?php

namespace App\Http\Controllers;

use App\Services\ShoppingPlanLogService;

class ShoppingPlanLogController extends Controller
{
    public function __construct(
        protected ShoppingPlanLogService $shoppingPlanLogService,
    ) {

    }

    public function getShoppingPlanLogByRecordId(string $id)
    {
        try {
            $result = $this->shoppingPlanLogService->getShoppingPlanLogByRecordId($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
