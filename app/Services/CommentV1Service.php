<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class CommentV1Service
{
    public function __construct(
        protected CommentRepository $commentRepository,
        protected UserRepository $userRepository,
    ) {

    }

    public function commentCreate(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = Auth::user()->id ?? 1;
        $data['message']    = $data['comment'];
        unset($data['comment']);

        $this->commentRepository->create($data);
    }
}
