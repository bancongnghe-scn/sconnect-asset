<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AppendixInfoResource extends JsonResource
{
    public function toArray($request)
    {
        $appendix = [
            'id'             => $this->resource->id,
            'code'           => $this->resource->code,
            'name'           => $this->resource->name,
            'contract_id'    => $this->resource->contract_id,
            'link'           => $this->resource->link,
            'signing_date'   => $this->resource->signing_date,
            'from'           => $this->resource->from,
            'to'             => $this->resource->to,
            'description'    => $this->resource->description,
            'user_ids'       => $this->resource->contractMonitors?->pluck('user_id')->toArray() ?? [],
        ];

        $files = $this->resource->contractFiles;
        foreach ($files as $file) {
            $appendix['files'][] = [
                'name' => $file->file_name,
                'url'  => Storage::disk('public')->url($file->file_url),
            ];
        }

        return $appendix;
    }
}
