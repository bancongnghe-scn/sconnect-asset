<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class AssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Asset::class;
    }

    public function changeStatusAsset($ids, $status)
    {
        $updatedRows = $this->_model->whereIn('id', Arr::wrap($ids))->update([
            'status' => $status,
        ]);

        return $updatedRows > 0;
    }

    public function getElementAssetByIds($ids, $columns = ['*'])
    {
        return $this->_model->whereIn('id', $ids)
            ->select($columns)
            ->get();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->select($columns)->with($with)->newQuery();

        if (!empty($filters['import_warehouse_id'])) {
            $query->where('import_warehouse_id', $filters['import_warehouse_id']);
        }

        return $query->get();
    }

    public function getAssetNeedMaintain($filters, $assetMaintainingIds = [], $columns = ['*'])
    {
        $date = Carbon::now()->addMonth()->format('Y-m-d');

        $query = $this->_model->select($columns)
            ->whereDate('next_maintenance_date', '<=', $date)
            ->whereIn('status', [Asset::STATUS_PENDING, Asset::STATUS_ACTIVE])
            ->whereNotIn('id', $assetMaintainingIds)
            ->orderBy('created_at', 'desc')
            ->newQuery();

        if (!empty($filters['name_code'])) {
            $query->where('name', $filters['name_code'])
                ->orWhere('code', $filters['name_code']);
        }

        if (!empty($filters['next_maintain_start']) && !empty($filters['next_maintain_end'])) {
            $query->whereBetween('next_maintenance_date', [$filters['next_maintain_start'], $filters['next_maintain_end']]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', $filters['location']);
        }

        if (!empty($filters['organization_ids'])) {
            $query->whereIn('organization_id', $filters['organization_ids']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getAssetNeedMaintainWithMonth($time)
    {
        $start = $time . '-01';
        $end   = $time . '-31';

        return $this->_model->whereBetween('next_maintenance_date', [$start, $end])
            ->whereIn('status', [Asset::STATUS_PENDING, Asset::STATUS_ACTIVE])
            ->get();

    }
}
