<?php

namespace App\Http\Resources;

use App\Services\ScApiService;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingPlanOrganizationResource extends JsonResource
{
    public function toArray($request)
    {
        $organizationId = $this->resource->organization_id;
        $organization   = ScApiService::getOrganizationByIds($organizationId)->first();

        return [
            'name'              => $this->resource->name,
            'status'            => $this->resource->status,
            'start_time'        => $this->resource->start_time,
            'end_time'          => $this->resource->end_time,
            'organization_name' => $organization['name'] ?? null,
        ];
    }
}
