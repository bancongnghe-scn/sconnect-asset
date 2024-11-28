<?php

namespace App\Repositories\Manage;

use App\Models\PlanMaintainAsset;
use App\Repositories\Base\BaseRepository;

class PlanMaintainAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainAsset::class;
    }

    public function getAssetOfPlanMaintain($planIds, $columns = ['*'])
    {
        $planIds = is_array($planIds) ? $planIds : [$planIds];

        return $this->_model->newQuery()
            ->whereIn('plan_maintain_id', $planIds)
            ->select($columns)
            ->get();
    }

    public function updateMulti($ids, $data)
    {
        return $this->_model->newQuery()
            ->whereIn('id', $ids)
            ->update($data);
    }
}
