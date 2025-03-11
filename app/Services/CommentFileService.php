<?php

namespace App\Services;

use App\Repositories\CommentFileRepository;
use App\Support\Constants\AppErrorCode;

class CommentFileService
{
    public function __construct(
        protected CommentFileRepository $commentFileRepository,
    ) {
    }

    public function insertCommentFiles(array $files, $commentId)
    {
        $commentFiles = [];
        foreach ($files as $file) {
            $path            = $file->store('comment_files', 'public');
            $commentFiles[]  = [
                'comment_id'  => $commentId,
                'file_url'    => $path,
                'file_name'   => $file->getClientOriginalName(),
            ];
        }
        $insert = $this->commentFileRepository->insert($commentFiles);
        if (!$insert) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2111,
            ];
        }

        return [
            'success' => true,
            'data'    => $commentFiles,
        ];
    }
}
