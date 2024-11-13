<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ListCommentResource extends JsonResource
{
    public function toArray($request)
    {
        $userLogin = Auth::id();
        $users     = $this->additional['users'] ?? [];
        $data      = [];
        $comments  = $this->resource->keyBy('id');
        foreach ($comments as $comment) {
            $replyUser = null;
            if (!empty($comment->reply)) {
                $replyUser = $users[$comments[$comment->reply]['created_at']]['name'] ?? null;
            }
            $data[] = [
                'id'            => $comment->id,
                'message'       => $comment->message,
                'reply_user'    => $replyUser,
                'user_login'    => $userLogin,
                'user_created'  => $users[$comment->created_by]['name'] ?? null,
                'created_at'    => date('H:i d/m/Y', strtotime($comment->created_at)),
                'created_by'    => $comment->created_by,
            ];
        }

        return $data;
    }
}
