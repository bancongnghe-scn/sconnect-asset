<?php

namespace App\Repositories;

use App\Models\PlanMaintainAssetType;
use App\Repositories\Base\BaseRepository;

class PlanMaintainAssetTypeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainAssetType::class;
    }

    public function getByPlanId($planId, $columns = ['*'])
    {
        return $this->_model->select($columns)->where('plan_maintain_id', $planId)->get();
    }
}
