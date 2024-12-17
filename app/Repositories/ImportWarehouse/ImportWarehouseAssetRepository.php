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

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['order_id'])) {
            $query->whereIn('order_id', Arr::wrap($filters['order_id']));
        }

        return $query->get();
    }
}
