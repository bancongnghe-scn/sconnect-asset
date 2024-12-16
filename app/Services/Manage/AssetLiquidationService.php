<?php

namespace App\Services\Manage;

use App\Http\Resources\Manage\AssetLiquidationResource;
use App\Models\Asset;
use App\Repositories\Manage\AssetLiquidationRepository;

class AssetLiquidationService
{
    public function __construct(
        protected AssetLiquidationRepository $assetLiquidationRepository,
    ) {

    }

    public function getListAssetLiquidation(array $filters = [])
    {
        $filters['status'] = Asset::STATUS_PROPOSAL_LIQUIDATION;
        $data              = $this->assetLiquidationRepository->getListAssetLiquidation(
            $filters,
            [
                'id',
                'name',
                'code',
                'status',
                'user_id',
            ],
            [
                'user:id,name',
                'assetHistory' => function ($query) {
                    $query->select('asset_id', 'date', 'description')
                        ->where('action', Asset::STATUS_PROPOSAL_LIQUIDATION)
                        ->orderBy('date', 'desc');
                },
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetLiquidationResource::make($data)->resolve();
    }
}
