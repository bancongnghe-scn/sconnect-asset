<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->resource->id,
            'name'        => $this->resource->name,
            'description' => $this->resource->description,
            'icon'        => $this->resource->icon,
            'url'         => $this->resource->url,
            'order'       => $this->resource->order,
            'parent_id'   => $this->resource->parent_id,
            'role_ids'    => $this->resource->menuRoles?->pluck('role_id')->toArray(),
            'user_ids'    => $this->resource->menuUsers?->pluck('user_id')->toArray(),
        ];
    }
}
