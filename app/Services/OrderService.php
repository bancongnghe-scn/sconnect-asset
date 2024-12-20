<?php

namespace App\Services;

use App\Models\OrderHistory;
use App\Repositories\OrderHistoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ShoppingAssetOrderRepository;
use App\Repositories\ShoppingAssetRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ShoppingAssetRepository $shoppingAssetRepository,
        protected OrderHistoryRepository $orderHistoryRepository,
        protected ShoppingAssetOrderRepository $shoppingAssetOrderRepository,
    ) {
    }

    public function getListOrder($filters)
    {
        $data = $this->orderRepository->getListing($filters);

        return $data->toArray();
    }

    public function createOrder($data)
    {
        DB::beginTransaction();
        try {
            $order                    = $this->orderRepository->create($data);
            $insertShoppingAssetOrder = resolve(ShoppingAssetOrderService::class)->insertShoppingAssetOrder($data['shopping_assets_order'], $order->id);
            if (!$insertShoppingAssetOrder) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2089,
                ];
            }

            $insert = $this->orderHistoryRepository->insertOrderHistory($order->id, OrderHistory::TYPE_CREATE_ORDER);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2090,
                ];
            }
            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function updateOrder($data)
    {
        $order = $this->orderRepository->find($data['id']);
        if (!empty($order)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2080,
            ];
        }

        DB::beginTransaction();
        try {
            $order->fill($data);
            if (!$order->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2091,
                ];
            }

            foreach ($data['shopping_assets_order'] as $shoppingAssetOrder) {
                $id = $shoppingAssetOrder['id'];
                unset($shoppingAssetOrder['id']);
                $update = $this->shoppingAssetOrderRepository->update($id, $shoppingAssetOrder);
                if (!$update) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2089,
                    ];
                }
            }

            $this->orderHistoryRepository->insertOrderHistory($data['id'], OrderHistory::TYPE_UPDATE_ORDER, $order->getAttributes(), $order->getAttributes());
            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }
}
