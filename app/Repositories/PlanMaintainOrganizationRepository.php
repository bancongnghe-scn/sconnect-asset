<?php

namespace App\Repositories;

use App\Models\PlanMaintainOrganization;
use App\Repositories\Base\BaseRepository;

class PlanMaintainOrganizationRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanMaintainOrganization::class;
    }
}
