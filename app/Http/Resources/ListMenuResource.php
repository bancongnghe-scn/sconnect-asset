<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListMenuResource extends JsonResource
{
    public function toArray($request)
    {
        $data  = [];
        $roles = $this->additional['roles'] ?? collect();
        foreach ($this->resource as $menu) {
            $roleIds   = $menu->role_ids;
            $roleIds   = explode(',', $roleIds);
            $roleNames = $roles->whereIn('id', $roleIds)->implode('name', ',');
            $data[]    = [
                'id'          => $menu->id,
                'name'        => $menu->name,
                'description' => $menu->description,
                'roles'       => $roleNames,
            ];
        }

        $menus = $this->resource->toArray();
        if (isset($menus['total'])) {
            $menus['data'] = $data;

            return $menus;
        }

        return $data;
    }
}
