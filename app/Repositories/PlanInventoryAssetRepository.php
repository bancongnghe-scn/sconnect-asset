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

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }

    public function updatePlanInventoryAssetById($id, $data)
    {
        $planInventoryAsset = $this->_model->find($id);
        if (empty($planInventoryAsset)) {
            return false;
        }

        $planInventoryAsset->fill($data);
        if ($planInventoryAsset->isDirty()) {
            return $planInventoryAsset->save();
        }

        return true;
    }
}
