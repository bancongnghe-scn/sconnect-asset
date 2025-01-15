<?php

namespace App\Repositories;

use App\Models\PlanMaintainCharge;
use App\Repositories\Base\BaseRepository;

class PlanMaintainChargeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainCharge::class;
    }
}
