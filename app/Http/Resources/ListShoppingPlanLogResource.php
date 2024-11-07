<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListShoppingPlanLogResource extends JsonResource
{
    public function toArray($request)
    {
        $data  = [];
        $users = $this->additional['users'] ?? [];
        foreach ($this->resource as $log) {
            $data[] = [
                'id'         => $log->id,
                'record_id'  => $log->record_id,
                'action'     => $log->action,
                'desc'       => $log->desc,
                'created_by' => $users[$log->created_by]['name'] ?? null,
                'created_at' => date('H:i d/m/Y', strtotime($log->created_at)),
            ];
        }

        return $data;
    }
}
