<?php

namespace App\Services;

use App\Http\Resources\InfoImportWarehouseResource;
use App\Http\Resources\ListImportWarehouseAssetResource;
use App\Models\ImportWarehouse;
use App\Models\Order;
use App\Repositories\ImportWarehouse\ImportWarehouseAssetRepository;
use App\Repositories\ImportWarehouse\ImportWarehouseRepository;
use App\Repositories\ImportWarehouseOrderRepository;
use App\Repositories\ShoppingAssetOrderRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportWarehouseService
{
    public function __construct(
        protected ImportWarehouseAssetRepository $importWarehouseAssetRepository,
        protected ShoppingAssetOrderRepository $shoppingAssetOrderRepository,
        protected ImportWarehouseOrderRepository $importWarehouseOrderRepository,
        protected ImportWarehouseRepository $importWarehouseRepository,
    ) {

    }

    public function getAssetForImportWarehouse($orderId)
    {
        $data = $this->importWarehouseAssetRepository->getListing(['order_id' => $orderId]);
        if ($data->isEmpty()) {
            $data = $this->shoppingAssetOrderRepository->getListing(['order_id' => $orderId]);
        }

        return ListImportWarehouseAssetResource::make($data)->resolve();
    }

    public function createImportWarehouse($data)
    {
        $importWarehouse = $this->importWarehouseRepository->getListing(['code' => $data['code'], 'first' => true]);
        if (!empty($importWarehouse)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2081,
            ];
        }

        $importWarehouseOrder = $this->importWarehouseOrderRepository->getListing(['order_id' => $data['order_id'], 'first' => true]);
        if (!empty($importWarehouseOrder)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2084,
            ];
        }

        DB::beginTransaction();
        try {
            $userId          = Auth::id();
            $importWarehouse = $this->importWarehouseRepository->create([
                'code'        => $data['code'],
                'name'        => $data['name'],
                'status'      => ImportWarehouse::STATUS_NOT_COMPLETE,
                'description' => $data['description'],
                'created_by'  => $userId,
            ]);

            $dataInsertImportWarehouseOrder = [];
            foreach ($data['order_ids'] as $orderId) {
                $dataInsertImportWarehouseOrder[] = [
                    'order_id'            => $orderId,
                    'import_warehouse_id' => $importWarehouse->id,
                    'created_by'          => $userId,
                ];
            }

            $insert = $this->importWarehouseOrderRepository->insert($dataInsertImportWarehouseOrder);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2082,
                ];
            }

            $importWarehouseAssets = [];
            foreach ($data['shopping_assets'] as $shoppingAsset) {
                $shoppingAsset['import_warehouse_id'] = $importWarehouse->id;
                $shoppingAsset['created_by']          = $userId;
                $importWarehouseAssets[]              = $shoppingAsset;
            }

            $insert = $this->importWarehouseAssetRepository->insert($importWarehouseAssets);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2083,
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

    public function getListImportWarehouse($filters)
    {
        $result = $this->importWarehouseRepository->getListing($filters);

        return $result->toArray();
    }

    public function getInfoImportWarehouse($id)
    {
        $importWarehouse = $this->importWarehouseRepository->find($id);
        if (empty($importWarehouse)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2085,
            ];
        }

        return [
            'success' => true,
            'data'    => InfoImportWarehouseResource::make($importWarehouse)->resolve(),
        ];
    }

    public function completeImportWarehouse($id)
    {
        $importWarehouse = $this->importWarehouseRepository->find($id);
        if (empty($importWarehouse)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2085,
            ];
        }

        $orderIds = $importWarehouse->importWarehouseOrders->pluck('order_id')->toArray();

        DB::beginTransaction();
        try {
            $importWarehouse->status = ImportWarehouse::STATUS_COMPLETE;
            if (!$importWarehouse->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2086,
                ];
            }

            // Thay doi trang thai don hang thanh da nhap kho
            $this->importWarehouseOrderRepository->updateByCondition(['order_id' => $orderIds], ['status' => Order::STATUS_WAREHOUSED]);

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
