<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\AssetHistoryRepository;
use App\Repositories\AssetRepository;
use App\Repositories\ImportWarehouse\ImportWarehouseAssetRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetService
{
    public function __construct(
        protected AssetRepository $assetRepository,
        protected ImportWarehouseAssetRepository $importWarehouseAssetRepository,
        protected AssetHistoryRepository $assetHistoryRepository,
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

    /**
     * @return array
     * đánh dấu tài sản
     */
    public function markAssets($data)
    {
        DB::beginTransaction();
        try {
            $update = $this->assetRepository->updateByCondition(['id' => $data['asset_ids']], ['status' => $data['status']]);
            if (!$update) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2128,
                ];
            }

            $data = [];
            foreach ($data['asset_ids'] as $assetId) {
                $data[] = [
                    'asset_id'    => $assetId,
                    'date'        => $data['date'],
                    'action'      => $data['status'],
                    'description' => $data['description'],
                    'price'       => $data['price'] ?? null,
                    'created_by'  => Auth::id(),
                ];
            }

            $insert = $this->assetHistoryRepository->insert($data);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }

            DB::commit();

            return [
                'success' => true,
            ];

        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }
}
