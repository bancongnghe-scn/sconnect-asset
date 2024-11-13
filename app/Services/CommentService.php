<?php

namespace App\Services;

use App\Events\ShoppingPlanCommentEvent;
use App\Http\Resources\ListCommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function __construct(
        protected CommentRepository $commentRepository,
        protected UserRepository $userRepository,
    ) {

    }

    public function getListComment(array $filters)
    {
        $result = $this->commentRepository->getListing($filters);

        if ($result->isEmpty()) {
            return [];
        }

        $userIds = $result->pluck('created_by')->toArray();
        $users   = $this->userRepository->getListing(['id' => $userIds])->keyBy('id');

        return ListCommentResource::make($result)->additional(['users' => $users])->resolve();
    }

    public function sentComment($data)
    {
        $user               = Auth::user();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $user['id'];

        $comment = $this->commentRepository->create($data);

        $replyUser = null;
        if (!empty($data['reply'])) {
            $reply = $this->commentRepository->find($data['reply']);
            if (!empty($reply)) {
                $replyUser = $this->userRepository->find($reply->created_by)?->name;
            }
        }

        switch ($data['type']) {
            case Comment::TYPE_SHOPPING_PLAN:
                ShoppingPlanCommentEvent::dispatch(
                    $data['target_id'],
                    $comment->id,
                    $data['message'],
                    $replyUser,
                    $user['name'],
                    date('H:i d/m/Y', strtotime($data['created_at']))
                );
                break;
            default:
        }
    }

    public function deleteComment($id)
    {
        $comment = $this->commentRepository->find($id);
        if (empty($comment)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2068,
            ];
        }

        if ($comment->created_by !== Auth::id()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2069,
            ];
        }

        if (!$comment->delete()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2070,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function editComment($data)
    {
        $comment = $this->commentRepository->find($data['id']);
        if (empty($comment)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2068,
            ];
        }

        $comment->message = $data['message'];
        if (!$comment->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2071,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
