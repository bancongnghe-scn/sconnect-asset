<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code'                 => $this->resource->code,
            'name'                 => $this->resource->name,
            'tax_code'             => $this->resource->tax_code,
            'contact'              => $this->resource->contact,
            'address'              => $this->resource->address,
            'contract_user'        => $this->resource->contract_user,
            'description'          => $this->resource->description,
            'meta_data'            => $this->resource->meta_data,
            'industry_ids'         => $this->resource->supplierAssetIndustries?->pluck('industries_id')->toArray(),
            'asset_type_ids'       => $this->resource->supplierAssetType?->pluck('asset_type_id')->toArray(),
        ];
    }
}
