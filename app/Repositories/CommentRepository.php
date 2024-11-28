<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Base\BaseRepository;

class CommentRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Comment::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['target_id'])) {
            $query->where('target_id', $filters['target_id']);
        }

        return $query->get();
    }
}
