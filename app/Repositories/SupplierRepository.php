<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class SupplierRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Supplier::class;
    }

    public function getListSupplierByFilters($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()
            ->select($columns);

        if (!empty($filters['code_name'])) {
            $query->where('supplier.name', 'like', $filters['code_name'] . '%')
            ->orWhere('supplier.code', $filters['code_name']);
        }

        if (!empty($filters['industry_ids'])) {
            $query->leftJoin('supplier_asset_industries','supplier_asset_industries.supplier_id', 'supplier.id');

            $query->whereIn('supplier_asset_industries.industries_id', Arr::wrap($filters['industry_ids']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('supplier.status', Arr::wrap($filters['status']));
        }

        return $query->get();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with)
            ->orderBy('created_at', 'desc');

        if (!empty($filters['ids'])) {
            $query->whereIn('id', $filters['ids']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getFirst($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with);

        if (!empty($filters['code'])) {
            $query->where('code', $filters['code']);
        }

        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        return $query->first();
    }

    public function createSupplier($data)
    {
        $dataCreate = [
            'name' => $data['name'],
            'code' => $data['code'],
            'contact' => $data['contact'] ?? null,
            'address' => $data['address'] ?? null,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'tax_code' => $data['tax_code'] ?? null,
            'meta_data' => $data['meta_data'] ?? null,
            'status' => Supplier::STATUS_PENDING_APPROVAL,
            'created_by' => User::USER_ID_ADMIN
        ];
        return $this->_model->newQuery()->create($dataCreate);
    }
}
