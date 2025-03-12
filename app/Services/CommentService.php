<?php

namespace App\Services;

use App\Events\OrderCommentEvent;
use App\Events\PlanMaintainCommentEvent;
use App\Events\ShoppingPlanCommentEvent;
use App\Events\ShoppingPlanOrganizationCommentEvent;
use App\Http\Resources\ListCommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function __construct(
        protected CommentRepository $commentRepository,
        protected UserRepository $userRepository,
    ) {

    }

    public function getListComment(array $filters)
    {
        $result = $this->commentRepository->getListing($filters, with: ['commentFiles']);

        if ($result->isEmpty()) {
            return [];
        }

        $userIds = $result->pluck('created_by')->toArray();
        $users   = $this->userRepository->getListing(['id' => $userIds])->keyBy('id');

        return ListCommentResource::make($result)->additional(['users' => $users])->resolve();
    }

    public function sentComment($data)
    {
        if (empty($data['files']) && empty($data['message'])) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2110,
            ];
        }
        $user               = Auth::user();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $user['id'];

        DB::beginTransaction();
        try {
            $comment     = $this->commentRepository->create($data);
            if (!empty($data['files'])) {
                $commentFiles = resolve(CommentFileService::class)->insertCommentFiles($data['files'], $comment->id);
                if (!$commentFiles['success']) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2111,
                    ];
                }
            }
            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        $dataComment = [
            'target_id'        => $data['target_id'],
            'id'               => $comment->id,
            'message'          => $data['message'] ?? null,
            'created_by'       => $user['id'],
            'created_at'       => date('H:i d/m/Y', strtotime($data['created_at'])),
            'user_created'     => $user['name'],
            'files'            => $commentFiles['data'] ?? [],
        ];
        switch ($data['type']) {
            case Comment::TYPE_SHOPPING_PLAN_COMPANY:
                ShoppingPlanCommentEvent::dispatch($dataComment);
                break;
            case Comment::TYPE_SHOPPING_PLAN_ORGANIZATION:
                ShoppingPlanOrganizationCommentEvent::dispatch($dataComment);
                break;
            case Comment::TYPE_PLAN_MAINTAIN:
                PlanMaintainCommentEvent::dispatch($dataComment);
                break;
            case Comment::TYPE_ORDER:
                OrderCommentEvent::dispatch($dataComment);
                break;
            default:
        }

        return [
            'success' => true,
        ];
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
