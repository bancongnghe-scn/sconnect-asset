<?php

namespace App\Services;

use App\Repositories\AssetTypeGroupRepository;

class AssetTypeGroupService
{
    public function __construct(
        protected AssetTypeGroupRepository $assetTypeGroupRepository
    )
    {

    }

    public function getListByFilters($filters, $columns)
    {
        $data = $this->assetTypeGroupRepository
    }
}
