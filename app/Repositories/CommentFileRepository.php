<?php

namespace App\Repositories;

use App\Models\CommentFile;
use App\Repositories\Base\BaseRepository;

class CommentFileRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return CommentFile::class;
    }
}
