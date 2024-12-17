<?php

namespace App\Repositories\ImportWarehouse;

use App\Models\ImportWarehouse;
use App\Repositories\Base\BaseRepository;

class ImportWarehouseRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ImportWarehouse::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->select($columns)->newQuery();

        if (!empty($filters['code'])) {
            $query->where('code', $filters['code']);
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }
}
