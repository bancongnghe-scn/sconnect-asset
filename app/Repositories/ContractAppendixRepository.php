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
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with)->orderBy('created_at', 'DESC');

        if (!empty($filters['name_code'])) {
            $query->where('code', $filters['name_code'])
                ->orWhere('name', 'LIKE', $filters['name_code'] . '%');
        }

        if (!empty($filters['contract_ids'])) {
            $query->whereIn('contract_id', Arr::wrap($filters['contract_ids']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['signing_date'])) {
            $query->whereDate('signing_date', $filters['signing_date']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('from', $filters['from']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getFirst($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['code'])) {
            $query->where('code', $filters['code']);
        }

        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        return $query->first();
    }

    public function deleteMultipleByIds($ids)
    {
        return $this->_model->whereIn('id', Arr::wrap($ids))->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
