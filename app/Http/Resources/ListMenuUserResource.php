<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListMenuUserResource extends JsonResource
{
    public function toArray($request)
    {
        $menus    = $this->resource->groupBy('parent_id');
        $menuMain = $menus[null];
        $menuMain->sortBy('order');
        $data = [];
        foreach ($menuMain as $menu) {
            $data[$menu->id]             = $menu;
            $menuChild                   = $menus[$menu->id] ?? collect();
            $data[$menu->id]['children'] = $menuChild->sortBy('order');
        }

        return $data;
    }
}
