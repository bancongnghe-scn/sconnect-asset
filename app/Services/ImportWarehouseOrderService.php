<?php

namespace App\Services;

use App\Repositories\ImportWarehouseOrderRepository;
use Illuminate\Support\Facades\Auth;

class ImportWarehouseOrderService
{
    public function __construct(
        protected ImportWarehouseOrderRepository $importWarehouseOrderRepository,
    ) {
    }

    public function insertImportWarehouseOrder(array $orderIds, $importWarehouseId)
    {
        $dataInsertImportWarehouseOrder = [];
        foreach ($orderIds as $orderId) {
            $dataInsertImportWarehouseOrder[] = [
                'order_id'            => $orderId,
                'import_warehouse_id' => $importWarehouseId,
                'created_by'          => Auth::id(),
            ];
        }

        return $this->importWarehouseOrderRepository->insert($dataInsertImportWarehouseOrder);
    }
}
