<?php

namespace App\Services;

use App\Http\Resources\ListSupplierResource;
use App\Repositories\IndustryRepository;
use App\Repositories\SupplierRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function __construct(
        protected SupplierRepository $supplierRepository,
        protected IndustryRepository $industryRepository,
    )
    {

    }

    public function getListSupplier(array $filters = [])
    {
        $listSupplier = $this->supplierRepository->getListSupplierByFilters($filters, ['supplier.id']);
        if ($listSupplier->isEmpty()) {
            return [
                'data' => [],
                'total' => 0,
                'last_page' => 1,
                'current_page' => 1,
            ];
        }

        $supplierIds = $listSupplier->pluck('id')->toArray();
        $listSupplier = $this->supplierRepository->getListing([
            'ids' => $supplierIds,
            'page' => $filters['page'] ?? 1,
            'limit' => $filters['limit'] ?? 10
        ], [
            'id','name','contact','address','website','level'
        ],
        [
            'supplierAssetIndustries:id,supplier_id,industries_id' => [
                'industry:id,name'
            ]
        ]);

        return ListSupplierResource::make($listSupplier)->resolve();
    }

    public function findSupplier($id)
    {
        $supplier = $this->supplierRepository->find($id);
        if (is_null($supplier)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2013
            ];
        }

        return [
            'success' => true,
            'data' => $supplier->toArray()
        ];
    }

    public function createSupplier($data)
    {
        $supplier = $this->supplierRepository->getFirst(['code' => $data['code']]);
        if (!empty($supplier)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2014
            ];
        }

        DB::beginTransaction();
        try {
            $supplier = $this->supplierRepository->createSupplier($data);

            resolve(SupplierAsseTypeService::class)->insertByAssetTypeIdsAndSupplierId(
                $data['asset_type_ids'], $supplier['id']
            );

            resolve(SupplierAssetIndustryService::class)->insertByIndustryIdsAndSupplierId(
                $data['industry_ids'], $supplier['id']
            );
            DB::commit();

        } catch (\Throwable $throwable) {
            DB::rollBack();

            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2015
            ];
        }

        return [
            'success' => true,
        ];
    }
}
