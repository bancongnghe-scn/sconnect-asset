<?php

namespace App\Repositories;

use App\Models\PlanMaintainSupplier;
use App\Repositories\Base\BaseRepository;

class PlanMaintainSupplierRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainSupplier::class;
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
