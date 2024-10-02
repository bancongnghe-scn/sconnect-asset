<?php

namespace App\Repositories;

use App\Models\Contract;
use App\Models\ContractFile;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ContractFileRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractFile::class;
    }

    public function deleteByContractIds($contractIds)
    {
        return $this->_model->whereIn('contract_id', Arr::wrap($contractIds))->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
