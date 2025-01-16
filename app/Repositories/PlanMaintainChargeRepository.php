<?php

namespace App\Repositories;

use App\Models\PlanMaintainCharge;
use App\Repositories\Base\BaseRepository;

class PlanMaintainChargeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainCharge::class;
    }

    public function deleteByCondition($filters)
    {
        $query = $this->_model->newQuery();
        if ($filters['plan_maintain_id']) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        return $query->delete();
    }
}
