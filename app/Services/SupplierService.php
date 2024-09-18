<?php

namespace App\Services;

use App\Http\Resources\ListSupplierResource;
use App\Repositories\IndustryRepository;
use App\Repositories\SupplierRepository;

class SupplierService
{
    public function __construct(
        protected SupplierRepository $supplierRepository,
        protected IndustryRepository $industryRepository
    )
    {

    }

    public function getListSupplier(array $filters = [])
    {
        $listSupplier = $this->supplierRepository->getListSupplierByFilters($filters, ['supplier.id']);
        if ($listSupplier->isEmpty()) {
            return [];
        }

        $supplierIds = $listSupplier->pluck('id')->toArray();
        $listSupplier = $this->supplierRepository->getListing(['ids' => $supplierIds], [
            'id','name','contact','address','website','level'
        ], []);

        $industryIds = $listSupplier->pluck('industries_id')->toArray();
        $listIndustry = $this->industryRepository->getListIndustry(['id' => $industryIds]);

        return ListSupplierResource::make($listSupplier)->additional([
            'list_industry' => $listIndustry->keyBy('id')->toArray()
        ])->resolve();
    }
}
