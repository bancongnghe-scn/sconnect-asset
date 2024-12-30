<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ListImportWarehouseResource extends JsonResource
{
    protected $userRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->userRepository = new UserRepository();
    }

    public function toArray($request)
    {
        $userIds = $this->resource->pluck('created_by')->toArray();
        $users   = $this->userRepository->getListing(['id' => $userIds])->keyBy('id')->toArray();
        $data    = [];
        foreach ($this->resource as $importWarehouse) {
            $data[] = [
                'id'         => $importWarehouse->id,
                'code'       => $importWarehouse->code,
                'name'       => $importWarehouse->name,
                'created_at' => $importWarehouse->created_at,
                'created_by' => $users[$importWarehouse->created_by] ?? [],
                'status'     => $importWarehouse->status,
            ];
        }

        $importWarehouses = $this->resource->toArray();
        if (isset($importWarehouses['total'])) {
            $importWarehouses['data'] = $data;

            return $importWarehouses;
        }

        return $data;

        return $data;
    }
}
