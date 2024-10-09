<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name'           => $this->resource->name,
            'description'    => $this->resource->description,
            'user_ids'       => $this->resource->usersPermission?->pluck('user_id')->toArray(),
            'role_ids'       => $this->resource->rolesPermission?->pluck('role_id')->toArray(),
        ];
    }
}
