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
                'id',
                'name',
                'code',
                'status',
                'user_id',
                'location',
            ],
            [
                'user:id,name',
                'assetHistory' => function ($query) {
                    $query->select('asset_id', 'date', 'description')
                        ->where('action', Asset::STATUS_CANCEL)
                        ->orderBy('date', 'desc');
                },
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetCancelResource::make($data)->resolve();
    }
}
