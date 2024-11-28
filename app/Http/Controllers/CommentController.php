<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService,
    ) {

    }

    public function getListComment(Request $request)
    {
        $request->validate([
            'type'      => 'required|integer',
            'target_id' => 'required|integer',
        ]);

        try {
            $result = $this->commentService->getListComment($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function sentComment(Request $request)
    {
        $request->validate([
            'type'      => 'required|integer',
            'target_id' => 'required|integer',
            'message'   => 'required|string',
            'reply'     => 'nullable|integer',
        ]);

        try {
            $this->commentService->sentComment($request->all());

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function deleteComment(string $id)
    {
        try {
            $result = $this->commentService->deleteComment($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function editComment(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'message' => 'required|string',
        ]);

        try {
            $result = $this->commentService->editComment($request->all());
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
