<?php

namespace App\Repositories;

use App\Models\ImportWarehouseOrder;
use App\Repositories\Base\BaseRepository;

class ImportWarehouseOrderRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ImportWarehouseOrder::class;
    }
}
