<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'           => $this->resource->name,
            'description'    => $this->resource->description,
            'user_ids'       => $this->resource->roleUsers?->pluck('model_id')->toArray(),
            'permission_ids' => $this->resource->rolePermissions?->pluck('permission_id')->toArray(),
        ];
    }
}
