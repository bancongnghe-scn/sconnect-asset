<?php

namespace App\Repositories\ImportWarehouse;

use App\Models\ImportWarehouseAsset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ImportWarehouseAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ImportWarehouseAsset::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['order_id'])) {
            $query->whereIn('order_id', Arr::wrap($filters['order_id']));
        }

        if (!empty($filters['code'])) {
            $query->whereIn('code', Arr::wrap($filters['code']));
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }

    public function deleteByCondition($filters)
    {
        $query = $this->_model->newQuery();
        if (!empty($filters['import_warehouse_id'])) {
            $query->where('import_warehouse_id', $filters['import_warehouse_id']);
        }

        return $query->delete();
    }
}
