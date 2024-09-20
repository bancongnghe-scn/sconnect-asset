<?php

namespace App\Repositories;

use App\Models\SupplierAsseType;
use App\Repositories\Base\BaseRepository;

class SupplierAsseTypeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return SupplierAsseType::class;
    }
}
