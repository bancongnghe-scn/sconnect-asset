<?php

namespace App\Repositories;

use App\Models\ShoppingAsset;
use App\Repositories\Base\BaseRepository;

class ShoppingAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingAsset::class;
    }
}
