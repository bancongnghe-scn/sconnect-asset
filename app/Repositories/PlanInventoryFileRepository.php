<?php

namespace App\Repositories;

use App\Models\PlanInventoryFile;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class PlanInventoryFileRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return PlanInventoryFile::class;
    }

    public function getFileUploadedLast($id)
    {
        return $this->_model->where('plan_maintain_id', $id)
            ->where('user_id', Auth::id())
            ->latest()->first();
    }
}
