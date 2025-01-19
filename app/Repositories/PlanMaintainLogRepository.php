<?php

namespace App\Repositories;

use App\Models\PlanMaintainLog;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class PlanMaintainLogRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainLog::class;
    }

    public function insertPlanMaintainLog($action, $planMaintainId, $newValue = [], $oldValue = [], $desc = null)
    {
        return $this->_model->insert([
            'action' => $action,
            'plan_maintain_id' => $planMaintainId,
            'new_value' => json_encode($newValue),
            'old_value' => json_encode($oldValue),
            'desc'       => $desc ?? __('plan_maintain_log.' . $action),
            'created_by' => Auth::id()
        ]);
    }

    public function getListing($filters)
    {
        $query = $this->_model->newQuery();

        if ($filters['plan_maintain_id']) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        return $query->get();
    }
}
