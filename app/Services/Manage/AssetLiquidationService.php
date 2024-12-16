<?php

namespace App\Services\Manage;

use App\Http\Resources\Manage\AssetLiquidationResource;
use App\Models\Asset;
use App\Repositories\Manage\assetLiquidationRepository;

class AssetLiquidationService
{
    public function __construct(
        protected assetLiquidationRepository $assetLiquidationRepository,
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
                'date',
                'reason',
                'price_liquidation',
            ],
            [
                'user:id,name',
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetLiquidationResource::make($data)->resolve();
    }
}
