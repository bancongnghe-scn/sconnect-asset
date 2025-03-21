<?php

namespace App\Repositories;

use App\Models\TransferAsset;
use App\Repositories\Base\BaseRepository;

class TransferAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return TransferAsset::class;
    }
}
