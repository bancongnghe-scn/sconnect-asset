<?php

namespace App\Repositories;

use App\Models\SupplierAssetIndustry;
use App\Repositories\Base\BaseRepository;

class SupplierAssetIndustryRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return SupplierAssetIndustry::class;
    }
}
