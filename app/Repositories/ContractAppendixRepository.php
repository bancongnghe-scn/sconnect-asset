<?php

namespace App\Repositories;

use App\Models\ContractAppendix;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ContractAppendixRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractAppendix::class;
    }

    public function deleteByContractIds($contractIds)
    {
        return $this->_model->whereIn('contract_id', Arr::wrap($contractIds))->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
