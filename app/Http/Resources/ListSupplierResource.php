<?php

namespace App\Http\Resources;

use App\Models\Supplier;
use Illuminate\Http\Resources\Json\JsonResource;

class ListSupplierResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $supplier) {
            $data[$supplier->id] = [
                'id'         => $supplier->id,
                'code'       => $supplier->code,
                'name'       => $supplier->name,
                'contact'    => $supplier->contact,
                'address'    => $supplier->address,
                'website'    => $supplier->website,
                'status'     => Supplier::STATUS_NAME[$supplier->status] ?? '',
                'industries' => [],
            ];

            $supplierAssetIndustries = $supplier->supplierAssetIndustries ?? [];
            foreach ($supplierAssetIndustries as $supplierIndustry) {
                if (!empty($supplierIndustry?->industry?->name)) {
                    $data[$supplier->id]['industries'][] = $supplierIndustry?->industry?->name;
                }
            }
        }

        $listSupplier = $this->resource->toArray();

        if (!empty($listSupplier['total'])) {
            $listSupplier['data'] = $data;

            return $listSupplier;
        }

        return $data;
    }
}
