<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListUserResource extends JsonResource
{
    public function toArray($request) {
        $jobTitles = $this->additional['job_titles'] ?? [];
        $data = [];
        foreach ($this->resource as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'code' => $user->code,
                'status' => $user->status,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'job_title_id' => $user->job_title_id,
                'job_title' => $jobTitles[$user->job_title_id]['name'] ?? '',
            ];
        }

        return $data;
    }
}
