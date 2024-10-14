<?php

namespace App\Http\Resources;

use App\Models\ContractAppendix;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListContractAppendixResource extends JsonResource
{
    public function toArray($request)
    {
        $data      = [];
        $now       = Carbon::now();
        foreach ($this->resource as $appendix) {
            $from     = Carbon::parse($appendix->from);
            $to       = Carbon::parse($appendix->to);
            $data[]   = [
                'id'             => $appendix->id,
                'code'           => $appendix->code,
                'name'           => $appendix->name,
                'contract_name'  => $appendix->contract?->name,
                'contract_code'  => $appendix->contract?->code,
                'signing_date'   => date('d/m/Y', strtotime($appendix->signing_date)),
                'status'         => ContractAppendix::STATUS_NAME[$appendix->status],
                'validity'       => $now->between($from, $to),
            ];
        }

        $appendix = $this->resource->toArray();
        if (isset($appendix['total'])) {
            $appendix['data'] = $data;

            return $appendix;
        }

        return $data;
    }
}
