<?php

namespace App\Repositories\Manage;

use App\Models\PlanMaintainAsset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class PlanMaintainAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainAsset::class;
    }

    public function getAssetOfPlanMaintain($planIds, $columns = ['*'])
    {
        return $this->_model->newQuery()
            ->select($columns)
            ->whereIn('plan_maintain_id', Arr::wrap($planIds))
            ->get();
    }

    public function updateMulti($ids, $data)
    {
        return $this->_model->newQuery()
            ->whereIn('id', $ids)
            ->update($data);
    }
}
