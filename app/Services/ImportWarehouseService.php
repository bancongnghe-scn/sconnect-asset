<?php

namespace App\Services;

use App\Http\Resources\InfoImportWarehouseResource;
use App\Http\Resources\ListImportWarehouseAssetResource;
use App\Http\Resources\ListImportWarehouseResource;
use App\Models\ImportWarehouse;
use App\Models\Order;
use App\Repositories\ImportWarehouse\ImportWarehouseAssetRepository;
use App\Repositories\ImportWarehouse\ImportWarehouseRepository;
use App\Repositories\ImportWarehouseOrderRepository;
use App\Repositories\OrderRepository;
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
        protected OrderRepository $orderRepository,
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

        $importWarehouseOrder = $this->importWarehouseOrderRepository->getListing(['order_id' => $data['order_ids'], 'first' => true]);
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

            $insert = resolve(ImportWarehouseOrderService::class)->insertImportWarehouseOrder($data['order_ids'], $importWarehouse->id);
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
                'data'    => [
                    'id' => $importWarehouse->id,
                ],
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

        return ListImportWarehouseResource::make($result)->resolve();
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
            $this->orderRepository->updateByCondition(['id' => $orderIds], ['status' => Order::STATUS_WAREHOUSED]);

            $generalAssets = resolve(AssetService::class)->generalAssetByImportWarehouse($id);
            if (!$generalAssets) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2087,
                ];
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            dd($exception);
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function updateImportWarehouse($id, array $dateUpdate)
    {
        $importWarehouse = $this->importWarehouseRepository->find($id);
        if (empty($importWarehouse)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2085,
            ];
        }

        $userId = Auth::id();
        DB::beginTransaction();
        try {
            $updateImportWarehouse = $importWarehouse->update([
                'name'        => $dateUpdate['name'],
                'code'        => $dateUpdate['code'],
                'description' => $dateUpdate['description'],
                'updated_by'  => $userId,
            ]);

            if (!$updateImportWarehouse) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2086,
                ];
            }

            $orderIdsOld = $importWarehouse->importWarehouseOrders->pluck('order_id')->toArray();
            $orderIdsAdd = array_diff($dateUpdate['order_ids'], $orderIdsOld);
            if (!empty($orderIdsAdd)) {
                $insert = resolve(ImportWarehouseOrderService::class)->insertImportWarehouseOrder($orderIdsAdd, $id);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2082,
                    ];
                }
            }
            $orderIdsRemove = array_diff($orderIdsOld, $dateUpdate['order_ids']);
            if (!empty($orderIdsRemove)) {
                $this->importWarehouseOrderRepository->deleteByCondition(['order_id' => $orderIdsRemove, 'import_warehouse_id' => $id]);
            }

            $dataInsert = [];
            foreach ($dateUpdate['shopping_assets'] as $shoppingAsset) {
                if (in_array($shoppingAsset['order_id'], $orderIdsAdd)) {
                    $shoppingAsset['import_warehouse_id'] = $id;
                    $shoppingAsset['created_by']          = $userId;
                    $dataInsert[]                         = $shoppingAsset;
                } else {
                    $id = $shoppingAsset['id'];
                    unset($shoppingAsset['id']);
                    $update = $this->importWarehouseAssetRepository->update($id, $shoppingAsset);
                    if (!$update) {
                        DB::rollBack();

                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2083,
                        ];
                    }
                }
            }

            if (!empty($dataInsert)) {
                $insert = $this->importWarehouseAssetRepository->insert($dataInsert);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2083,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];

        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);
            dd($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function deleteImportWarehouse($id)
    {
        $importWarehouse = $this->importWarehouseRepository->find($id);
        if (empty($importWarehouse)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2085,
            ];
        }

        DB::beginTransaction();
        try {
            if (!$importWarehouse->delete()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2088,
                ];
            }

            $this->importWarehouseOrderRepository->deleteByCondition(['import_warehouse_id' => $id]);
            $this->importWarehouseAssetRepository->deleteByCondition(['import_warehouse_id' => $id]);
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
