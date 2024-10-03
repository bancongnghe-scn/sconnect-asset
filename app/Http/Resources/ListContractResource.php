<?php

namespace App\Http\Resources;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListContractResource extends JsonResource
{
    public function toArray($request)
    {
        $suppliers = $this->additional['suppliers'] ?? [];
        $now       = Carbon::now();
        $data      = [];
        foreach ($this->resource as $contract) {
            $supplier = $suppliers[$contract->supplier_id] ?? [];
            $from     = Carbon::parse($contract->from);
            $to       = Carbon::parse($contract->to);
            $data[]   = [
                'id'             => $contract->id,
                'code'           => $contract->code,
                'type'           => Contract::TYPE_NAME[$contract->type],
                'name'           => $contract->name,
                'supplier_name'  => $supplier['name'] ?? null,
                'signing_date'   => $contract->signing_date,
                'contract_value' => $contract->contract_value,
                'validity'       => $now->between($from, $to),
                'status'         => Contract::STATUS_NAME[$contract->status],
            ];
        }

        $contracts = $this->resource->toArray();
        if (isset($contracts['total'])) {
            return [
                'data'         => $data,
                'total'        => $contracts['total'],
                'last_page'    => $contracts['last_page'],
                'current_page' => $contracts['current_page'],
            ];
        }

        return $data;
    }
}
