<?php

namespace App\Repositories;

use App\Models\ShoppingPlanOrganization;
use App\Repositories\Base\BaseRepository;

class ShoppingPlanOrganizationRepository extends BaseRepository
{

    public function getModelClass(): string
    {
        return ShoppingPlanOrganization::class;
    }
}
