<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetRepository;
use App\Repositories\ImportWarehouse\ImportWarehouseAssetRepository;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
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
            $data = [
                'name'                    => $importWarehouseAsset->name,
                'code'                    => $importWarehouseAsset->code,
                'price'                   => $importWarehouseAsset->price_last,
                'warranty_months'         => $importWarehouseAsset->warranty_time,
                'depreciation_months'     => $importWarehouseAsset->assetType?->depreciation_months,
                'recent_maintenance_date' => $importWarehouseAsset->date_purchase,
                'next_maintenance_date'   => Carbon::create($importWarehouseAsset->date_purchase)
                    ->addMonths($importWarehouseAsset->assetType?->depreciation_months)
                    ->format('Y-m-d'),
                'asset_type_id'           => $importWarehouseAsset->asset_type_id,
                'supplier_id'             => $importWarehouseAsset->supplier_id,
                'status'                  => Asset::STATUS_PENDING,
                'import_warehouse_id'     => $importWarehouseId,
                'created_by'              => $userId,
            ];

            $dataInsert[] = $data;
        }

        $insert = $this->assetRepository->insert($dataInsert);
        if (!$insert) {
            return $insert;
        }

        $assets = $this->assetRepository->getListing(['import_warehouse_id' => $importWarehouseId]);
        foreach ($assets as $asset) {
            $link     = config('app.url').'/assets/info/'.$asset->id;
            $savePath = storage_path('app/public/qrcode/qr_image_'.$asset->id.'.png');
            $qrCode   = Builder::create()
                ->writer(new PngWriter())
                ->data($link)
                ->size(300)
                ->margin(10)
                ->build();

            $qrCode->saveToFile($savePath);
        }

        return true;
    }
}
