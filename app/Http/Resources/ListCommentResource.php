<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListCommentResource extends JsonResource
{
    public function toArray($request)
    {
        $users    = $this->additional['users'] ?? [];
        $data     = [];
        $comments = $this->resource->keyBy('id');
        foreach ($comments as $comment) {
            $replyUser = null;
            if (!empty($comment->reply)) {
                $replyUser = $users[$comments[$comment->reply]['created_at']]['name'] ?? null;
            }
            $data[] = [
                'id'         => $comment->id,
                'message'    => $comment->message,
                'reply_user' => $replyUser,
                'created_by' => $users[$comment->created_by]['name'] ?? null,
                'created_at' => date('H:i d/m/Y', strtotime($comment->created_at)),
            ];
        }

        return $data;
    }
}
