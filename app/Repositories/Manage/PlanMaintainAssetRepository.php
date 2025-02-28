<?php

namespace App\Repositories\Manage;

use App\Models\PlanMaintainAsset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->select($columns)->newQuery();

        if (!empty($filters['name_code'])) {
            $query->where('name', 'like', '%' . $filters['name_code'] . '%')
                ->orWhere('code', 'like', '%' . $filters['name_code'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['plan_maintain_id'])) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        if (!empty($filters['start_date_maintain']) && !empty($filters['complete_date_maintain'])) {
            $query->where('start_date_maintain', '>=', $filters['start_date_maintain'])
                ->where('complete_date_maintain', '<=', $filters['complete_date_maintain']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', $filters['location']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function deleteByCondition($filters)
    {
        $query = $this->_model->newQuery();

        if (!empty($filters['plan_maintain_id'])) {
            $query->where('plan_maintain_id', $filters['plan_maintain_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['asset_id'])) {
            $query->where('asset_id', $filters['asset_id']);
        }

        return $query->delete();
    }

    public function updateWithConditions($caseString, $idString)
    {
        $sql = "
            UPDATE plan_maintain_asset
            SET price = CASE $caseString END
            WHERE (asset_id, plan_maintain_id) IN ($idString)
            AND deleted_at IS NULL;
        ";

        return DB::affectingStatement($sql); // return number records updated
    }
}
