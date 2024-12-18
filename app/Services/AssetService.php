<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetRepository;
use App\Repositories\ImportWarehouse\ImportWarehouseAssetRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AssetService
{
    public function __construct(
        protected AssetRepository $assetRepository,
        protected ImportWarehouseAssetRepository $importWarehouseAssetRepository,
    ) {

    }

    public function generalAssetByImportWarehouse($importWarehouseId)
    {
        $importWarehouseAssets = $this->importWarehouseAssetRepository->getListing(
            ['import_warehouse_id' => $importWarehouseId],
            with: ['assetType']
        );

        $dataInsert = [];
        $userId     = Auth::id();
        foreach ($importWarehouseAssets as $importWarehouseAsset) {
            $dataInsert[] = [
                'name'                    => $importWarehouseAsset->name,
                'asset_type_id'           => $importWarehouseAsset->asset_type_id,
                'code'                    => $importWarehouseAsset->code,
                'supplier_id'             => $importWarehouseAsset->supplier_id,
                'price'                   => $importWarehouseAsset->price_last,
                'warranty_months'         => $importWarehouseAsset->warranty_time,
                'depreciation_months'     => $importWarehouseAsset->assetType?->depreciation_months,
                'recent_maintenance_date' => $importWarehouseAsset->date_purchase,
                'next_maintenance_date'   => Carbon::create($importWarehouseAsset->date_purchase)
                    ->addMonths($importWarehouseAsset->assetType?->depreciation_months)
                    ->format('Y-m-d'),
                'status'              => Asset::STATUS_NEW,
                'import_warehouse_id' => $importWarehouseId,
                'created_by'          => $userId,
            ];
        }

        return $this->assetRepository->insert($dataInsert);
    }
}
