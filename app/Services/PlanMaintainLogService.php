<?php

namespace App\Services;

use App\Repositories\PlanMaintainLogRepository;

class PlanMaintainLogService
{
    public function __construct(protected PlanMaintainLogRepository $planMaintainLogRepository)
    {

    }

    public function getPlanMaintainLogById($id)
    {
        $logs = $this->planMaintainLogRepository->getListing(['plan_maintain_id' => $id]);
        return $logs->toArray();
    }
}
