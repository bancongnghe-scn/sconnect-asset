<?php

namespace App\Repositories;

use App\Models\ImportWarehouseOrder;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ImportWarehouseOrderRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ImportWarehouseOrder::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->select($columns)->newQuery();

        if (!empty($filters['order_id'])) {
            $query->whereIn('order_id', Arr::wrap($filters['order_id']));
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }

    public function deleteByCondition($filters)
    {
        $query = $this->_model->newQuery();
        if (!empty($filters['order_id'])) {
            $query->whereIn('order_id', Arr::wrap($filters['order_id']));
        }

        if (!empty($filters['import_warehouse_id'])) {
            $query->whereIn('import_warehouse_id', Arr::wrap($filters['import_warehouse_id']));
        }

        return $query->delete();
    }
}
