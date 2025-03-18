<?php

namespace App\Repositories;

use App\Models\PlanInventoryAsset;
use App\Repositories\Base\BaseRepository;

class PlanInventoryAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanInventoryAsset::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['plan_maintain_id'])) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        return $query->get();
    }
}
