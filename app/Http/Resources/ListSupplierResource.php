<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListSupplierResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $supplier) {
            $data[$supplier->id] = [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'contact' => $supplier->contact,
                'address' => $supplier->address,
                'website' => $supplier->website,
                'level' => $supplier->level,
                'industries' => []
            ];

            $supplierAssetIndustries = $supplier->supplierAssetIndustries ?? [];
            foreach ($supplierAssetIndustries as $supplierIndustry) {
                if (!empty($supplierIndustry?->industry?->name)) {
                    $data[$supplier->id]['industries'][] = $supplierIndustry?->industry?->name;
                }
            }
        }

        $listSupplier = $this->resource->toArray();
        return [
            'data' => $data,
            'total' => $listSupplier['total'] ?? 0,
            'last_page' => $listSupplier['last_page'] ?? 1,
            'current_page' => $listSupplier['current_page'] ?? 1,
        ];
    }
}
