<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfoOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                       => $this->resource->id,
            'name'                     => $this->resource->name,
            'type'                     => $this->resource->type,
            'supplier_id'              => $this->resource->supplier_id,
            'shopping_plan_company_id' => $this->resource->shopping_plan_company_id,
            'purchasing_manager_id'    => $this->resource->purchasing_manager_id,
            'delivery_date'            => $this->resource->delivery_date,
            'delivery_location'        => $this->resource->delivery_location,
            'contact_person'           => $this->resource->contact_person,
            'contract_info'            => $this->resource->contract_info,
            'payment_time'             => $this->resource->payment_time,
            'status'                   => $this->resource->status,
            'shipping_costs'           => $this->resource->shipping_costs,
            'other_costs'              => $this->resource->other_costs,
            'plan_name'                => $this->resource->shoppingPlanCompany?->name,
            'supplier_name'            => $this->resource->supplier?->name,
        ];
    }
}
