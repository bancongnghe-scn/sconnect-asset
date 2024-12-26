<?php

namespace App\Services;

use App\Http\Resources\ListOrderResource;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Repositories\OrderHistoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ShoppingAssetOrderRepository;
use App\Repositories\ShoppingAssetRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
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

        return ListOrderResource::make($data)->resolve();
    }

    public function createOrder($data)
    {
        $order = $this->orderRepository->getListing([
            'shopping_plan_company_id' => $data['shopping_plan_company_id'],
            'supplier_id'              => $data['supplier_id'],
            'status'                   => [Order::STATUS_NEW, Order::STATUS_TRANSIT, Order::STATUS_DELIVERED, Order::STATUS_WAREHOUSED],
            'first'                    => true,
        ]);
        if (!empty($order)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2092,
            ];
        }

        $data['code']       = $this->generalCodeOrder();
        $data['created_by'] = Auth::id();
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
            dd($exception);
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

    public function deleteOrder(array $ids, $reason)
    {
        $delete = $this->orderRepository->updateByCondition(['id' => $ids], [
            'status' => Order::STATUS_CANCEL,
            'reason' => $reason,
        ]);

        if (!$delete) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2093,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function generalCodeOrder()
    {
        $orderLate = $this->orderRepository->getLateOrder();
        $id        = $orderLate->id ?? 0;
        if ($id < 10) {
            return 'DH0'.$id;
        }

        return 'DH'.$id;
    }

    public function findOrder($id)
    {
        $order = $this->orderRepository->find($id);
        if (empty($order)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2080,
            ];
        }

        return [
            'success' => true,
            'data'    => $order->toArray(),
        ];
    }
}
