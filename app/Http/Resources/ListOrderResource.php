<?php

namespace App\Http\Resources;

use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ListOrderResource extends JsonResource
{
    protected $supplierRepository;
    protected $userRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->supplierRepository = new SupplierRepository();
        $this->userRepository     = new UserRepository();
    }

    public function toArray($request)
    {
        $data = [];

        $suppliers   = [];
        $supplierIds = $this->resource->pluck('supplier_id')->toArray();
        if (!empty($supplierIds)) {
            $suppliers = $this->supplierRepository->getListing(['id' => $supplierIds])->keyBy('id')->toArray();
        }

        $users   = [];
        $userIds = $this->resource->pluck('purchasing_manager_id')->toArray();
        if (!empty($userIds)) {
            $users = $this->userRepository->getListing(['id' => $userIds])->keyBy('id')->toArray();
        }
        foreach ($this->resource as $order) {
            $order->supplier_name      = $suppliers[$order->supplier_id]['name'] ?? null;
            $order->purchasing_manager = $users[$order->purchasing_manager_id] ?? null;
            $data[]                    = $order->toArray();
        }

        $orders = $this->resource->toArray();
        if (isset($orders['total'])) {
            $orders['data'] = $data;

            return $orders;
        }

        return $data;
    }
}
