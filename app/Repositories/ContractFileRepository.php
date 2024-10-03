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

    public function getListing(array $filters)
    {
        $query = $this->_model->newQuery();

        if (!empty($filters['contract_id'])) {
            $query->whereIn('contract_id', Arr::wrap($filters['contract_id']));
        }

        if (!empty($filters['file_name'])) {
            $query->whereIn('file_name', Arr::wrap($filters['file_name']));
        }

        return $query->get();
    }

    public function deleteFilesOfContract($fileNames, $contractId)
    {
        return $this->_model->whereIn('file_name', Arr::wrap($fileNames))
            ->where('contract_id', $contractId)->update([
                'deleted_by' => Auth::id(),
                'deleted_at' => date('Y-m-d H:i:s')
            ]);
    }
}
