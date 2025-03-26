<?php

namespace App\Http\Resources;

use App\Repositories\Rbac\MenuRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ListMenuUserResource extends JsonResource
{
    protected $menuRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->menuRepository = new MenuRepository();
    }

    public function toArray($request)
    {
        $menus     = $this->resource->groupBy('parent_id');
        $parentIds = $this->resource->pluck('parent_id')->toArray();
        $menuMain  = $this->menuRepository->getListing(['id' => array_unique($parentIds)]);
        $menuMain  = $menuMain->sortBy('order');
        $data      = [];
        foreach ($menuMain as $menu) {
            $data[$menu->id]             = $menu;
            $menuChild                   = $menus[$menu->id] ?? collect();
            $data[$menu->id]['children'] = array_values($menuChild->sortBy('order')->toArray());

        }

        return $data;
    }
}
