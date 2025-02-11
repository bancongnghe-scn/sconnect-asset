<?php

namespace App\Services;

use App\Http\Resources\ListSupplierResource;
use App\Http\Resources\SupplierInfoResource;
use App\Repositories\IndustryRepository;
use App\Repositories\SupplierRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function __construct(
        protected SupplierRepository $supplierRepository,
        protected IndustryRepository $industryRepository,
    ) {

    }

    public function getListSupplier(array $filters = [])
    {
        $listSupplier = $this->supplierRepository->getListSupplierByFilters($filters, ['supplier.id']);
        if ($listSupplier->isEmpty()) {
            return [];
        }

        $supplierIds  = $listSupplier->pluck('id')->toArray();
        $listSupplier = $this->supplierRepository->getListing(
            [
                'ids'   => $supplierIds,
                'page'  => $filters['page'] ?? 1,
                'limit' => $filters['limit'] ?? 10,
            ],
            [
                'id', 'code', 'name', 'contact', 'address', 'contract_user', 'status',
            ],
            [
                'supplierAssetIndustries:id,supplier_id,industries_id' => [
                    'industry:id,name',
                ],
            ]
        );

        return ListSupplierResource::make($listSupplier)->resolve();
    }

    public function findSupplier($id)
    {
        $supplier = $this->supplierRepository->getFirst(['id' => $id], with: ['supplierAssetIndustries', 'supplierAssetType']);
        if (empty($supplier)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2013,
            ];
        }

        return [
            'success' => true,
            'data'    => SupplierInfoResource::make($supplier)->resolve(),
        ];
    }

    public function createSupplier($data)
    {
        $supplier = $this->supplierRepository->getFirst(['code' => $data['code']]);
        if (!empty($supplier)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2014,
            ];
        }

        DB::beginTransaction();
        try {
            $supplier = $this->supplierRepository->createSupplier($data);

            resolve(SupplierAssetTypeService::class)->insertByAssetTypeIdsAndSupplierId(
                $data['asset_type_ids'],
                $supplier['id']
            );

            resolve(SupplierAssetIndustryService::class)->insertByIndustryIdsAndSupplierId(
                $data['industry_ids'],
                $supplier['id']
            );
            DB::commit();

        } catch (\Throwable $throwable) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2015,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function deleteSupplierById($id)
    {
        $supplier = $this->supplierRepository->find($id);
        if (is_null($supplier)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2013,
            ];
        }

        if (!$supplier->delete()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2016,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateSupplier($data, $id)
    {
        $supplier = $this->supplierRepository->find($id);
        if (is_null($supplier)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2013,
            ];
        }

        DB::beginTransaction();
        try {
            $supplier->fill($data);
            if (!$supplier->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2017,
                ];
            }

            $updateSupplierAssetType = resolve(SupplierAssetTypeService::class)->updateSupplierAssetType(
                $data['asset_type_ids'],
                $id
            );
            if (!$updateSupplierAssetType['success']) {
                DB::rollBack();

                return $updateSupplierAssetType;
            }

            $updateSupplierAssetIndustry = resolve(SupplierAssetIndustryService::class)->updateSupplierAssetIndustry(
                $data['industry_ids'],
                $id
            );
            if (!$updateSupplierAssetIndustry['success']) {
                DB::rollBack();

                return $updateSupplierAssetIndustry;
            }

            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2017,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function deleteSupplierMultiple($ids)
    {
        $result = $this->supplierRepository->deleteMultipleByIds($ids);
        if (!$result) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2054,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
