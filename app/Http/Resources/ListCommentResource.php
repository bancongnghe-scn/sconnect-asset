<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListCommentResource extends JsonResource
{
    public function toArray($request)
    {
        $users     = $this->additional['users'] ?? [];
        $data      = [];
        $comments  = $this->resource->keyBy('id');
        foreach ($comments as $comment) {
            $data[] = [
                'id'            => $comment->id,
                'message'       => $comment->message,
                'user_created'  => $users[$comment->created_by]['name'] ?? null,
                'created_at'    => date('H:i d/m/Y', strtotime($comment->created_at)),
                'created_by'    => $comment->created_by,
            ];
        }

        return $data;
    }
}
