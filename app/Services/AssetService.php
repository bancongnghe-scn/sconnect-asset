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

        $dataInsert   = [];
        $userId       = Auth::id();
        $datePurchase = Carbon::now();
        foreach ($importWarehouseAssets as $importWarehouseAsset) {
            $data = [
                'name'                    => $importWarehouseAsset->name,
                'code'                    => $importWarehouseAsset->code,
                'price'                   => $importWarehouseAsset->price_last,
                'date_purchase'           => $datePurchase,
                'warranty_months'         => $importWarehouseAsset->warranty_time,
                'seri_number'             => $importWarehouseAsset->seri_number,
                'depreciation_months'     => +$importWarehouseAsset->price_last >= Asset::PRICE_DEPRECIATION ?
                    Asset::MONTH_DEPRECIATION_36 : Asset::MONTH_DEPRECIATION_12,
                'recent_maintenance_date' => $importWarehouseAsset->date_purchase,
                'next_maintenance_date'   => Carbon::create($importWarehouseAsset->date_purchase)
                    ->addMonths($importWarehouseAsset->assetType?->depreciation_months)
                    ->format('Y-m-d'),
                'asset_type_id'           => $importWarehouseAsset->asset_type_id,
                'supplier_id'             => $importWarehouseAsset->supplier_id,
                'status'                  => Asset::STATUS_PENDING,
                'location'                => Asset::LOCATION_WAREHOUSE,
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
            $this->generalQrCodeAsset($asset->id);
        }

        return true;
    }

    public function generalQrCodeAsset($assetId)
    {
        $link     = config('app.url').'/asset/info/'.$assetId;
        $savePath = public_path('uploads/qrcode/qr_image_'.$assetId.'.png');
        $qrCode   = Builder::create()
            ->writer(new PngWriter())
            ->data($link)
            ->size(300)
            ->margin(10)
            ->build();

        $qrCode->saveToFile($savePath);
    }
}
