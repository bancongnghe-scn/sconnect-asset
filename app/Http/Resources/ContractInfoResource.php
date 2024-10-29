<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ContractInfoResource extends JsonResource
{
    public function toArray($request)
    {
        $contract = [
            'id'             => $this->resource->id,
            'code'           => $this->resource->code,
            'type'           => $this->resource->type,
            'name'           => $this->resource->name,
            'supplier_id'    => $this->resource->supplier_id,
            'signing_date'   => $this->resource->signing_date,
            'from'           => $this->resource->from,
            'to'             => $this->resource->to,
            'contract_value' => $this->resource->contract_value,
            'description'    => $this->resource->description,
        ];

        $files = $this->resource->contractFiles ?? [];
        foreach ($files as $file) {
            $contract['files'][] = [
                'name' => $file->file_name,
                'url'  => Storage::disk('public')->url($file->file_url),
            ];
        }

        $payments = $this->resource->contractPayments ?? [];
        foreach ($payments as $payment) {
            $contract['payments'][] = [
                'payment_date' => $payment->payment_date,
                'money'        => $payment->money,
                'description'  => $payment->description,
            ];
        }

        $users = $this->resource->contractMonitors ?? [];
        foreach ($users as $user) {
            $contract['user_ids'][] = $user->user_id;
        }

        $contractAppendix = $this->resource->contractAppendixApproval ?? [];
        foreach ($contractAppendix as $appendix) {
            $contract['appendixes'][] = [
                'code'         => $appendix->code,
                'name'         => $appendix->name,
                'signing_date' => $appendix->signing_date,
                'from'         => $appendix->from,
                'description'  => $appendix->description,
            ];
        }

        return $contract;
    }
}
