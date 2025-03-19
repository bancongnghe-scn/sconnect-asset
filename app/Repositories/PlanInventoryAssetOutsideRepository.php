<?php

namespace App\Repositories;

use App\Models\PlanInventoryAssetOutside;
use App\Repositories\Base\BaseRepository;

class PlanInventoryAssetOutsideRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanInventoryAssetOutside::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['plan_maintain_id'])) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        return $query->get();
    }
}
