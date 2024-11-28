<?php

namespace App\Services\Manage;

use App\Repositories\Manage\AssetCancelRepository;
use App\Http\Resources\Manage\AssetCancelResource;
use App\Models\Asset;

class AssetCancelService
{
    public function __construct(
        protected AssetCancelRepository $assetCancelRepository,
    ) {

    }

    public function getListAssetCancel(array $filters = [])
    {
        $filters['status'] = Asset::STATUS_CANCEL;

        $data = $this->assetCancelRepository->getListAssetCancel(
            $filters,
            [
                'name',
                'code',
                'status',
                'user_id',
                'date',
                'location',
                'reason',
            ],
            [
                'user:id,name',
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetCancelResource::make($data)->resolve();
    }
}
