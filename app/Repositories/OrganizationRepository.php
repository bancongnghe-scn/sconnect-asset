<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Repositories\Base\BaseRepository;

class OrganizationRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Organization::class;
    }
}
