<?php

namespace App\Repositories;

use App\Models\PlanMaintainSupplier;
use App\Repositories\Base\BaseRepository;

class PlanMaintainSupplierRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainSupplier::class;
    }
}
