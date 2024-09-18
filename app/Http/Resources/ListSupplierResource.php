<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListSupplierResource extends JsonResource
{
    public function toArray($request)
    {
        $listIndustry = $this->additional['list_industry'] ?? [];
        $data = [];
        foreach ($this->resource as $supplier) {
            if (empty($data[$supplier->id])) {
                $data[$supplier->id] = [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'contact' => $supplier->contact,
                    'address' => $supplier->address,
                    'website' => $supplier->website,
                    'level' => $supplier->level,
                ];
            }

            $industry = $listIndustry[$supplier['industries_id']];
            $data['industries'][] = $industry['name'];
        }

        $listSupplier = $this->resource->toArray();
        return [
            'data' => $data,
            'total' => $listSupplier['total'] ?? null,
            'last_page' => $listSupplier['last_page'] ?? null,
            'current_page' => $listSupplier['current_page'] ?? null,
        ];
    }
}
