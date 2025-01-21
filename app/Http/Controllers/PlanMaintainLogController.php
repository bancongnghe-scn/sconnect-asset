<?php

namespace App\Http\Controllers;

use App\Services\PlanMaintainLogService;

class PlanMaintainLogController extends Controller
{
    public function __construct(
        protected PlanMaintainLogService $planMaintainLogService,
    ) {

    }

    public function getPlanMaintainLogById(string $id)
    {
        try {
            $result = $this->planMaintainLogService->getPlanMaintainLogById($id);

            return response_success($result);
        } catch (\Throwable $e) {
            report($e);

            return response_error();
        }
    }
}
