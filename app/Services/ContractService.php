<?php

namespace App\Services;

use App\Http\Resources\ListContractResource;
use App\Models\Contract;
use App\Models\ContractAppendix;
use App\Repositories\ContractAppendixRepository;
use App\Repositories\ContractFileRepository;
use App\Repositories\ContractMonitorRepository;
use App\Repositories\ContractPaymentRepository;
use App\Repositories\ContractRepository;
use App\Repositories\SupplierRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractService
{
    public function __construct(
        protected ContractRepository $contractRepository,
        protected ContractMonitorRepository $contractMonitorRepository,
        protected ContractFileRepository $contractFileRepository,
        protected ContractPaymentRepository $contractPaymentRepository,
        protected SupplierRepository $supplierRepository,
        protected ContractAppendixRepository $contractAppendixRepository
    )
    {

    }

    public function createContract($data)
    {
        $contract = $this->contractRepository->getFirst(['code' => $data['code']]);
        if (!empty($contract)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2023,
            ];
        }

        $data['created_by'] = Auth::id();
        $data['status'] = Contract::STATUS_PENDING;
        DB::beginTransaction();
        try {
            $contract = $this->contractRepository->create($data);
            $dataCreateContractMonitor = [];
            foreach ($data['user_ids'] as $userId) {
                $dataCreateContractMonitor[] = [
                    'contract_id' => $contract->id,
                    'user_id' => $userId,
                ];
            }
            $insertContractMonitor = $this->contractMonitorRepository->insert($dataCreateContractMonitor);
            if (!$insertContractMonitor) {
                DB::rollBack();
                return [
                    'success' => false,
                    'error_code' => AppErrorCode::CODE_2025,
                ];
            }

            $files = $data['files'] ?? [];
            if (!empty($files)) {
                $contractFiles = [];
                foreach ($files as $file) {
                    $path = $file->store('uploads');
                    $contractFiles[] = [
                        'contract_id' => $contract->id,
                        'file_url' => $path
                    ];
                }
                $insertContractFiles = $this->contractFileRepository->insert($contractFiles);
                if (!$insertContractFiles) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'error_code' => AppErrorCode::CODE_2026,
                    ];
                }
            }

            $payments = $data['payments'] ?? [];
            if (!empty($payments)) {
                $insertContractPayments = resolve(ContractPaymentService::class)->createContractPayment($payments, $contract->id);
                if (!$insertContractPayments) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'error_code' => AppErrorCode::CODE_2027,
                    ];
                }
            }

            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2024,
            ];
        }

        return [
           'success' => true,
        ];
    }

    public function getListContract(array $filters)
    {
        $data = $this->contractRepository->getListing($filters);

        $supplierIds = $data->pluck('supplier_id')->toArray();
        $suppliers = [];
        if (!empty($supplierIds)) {
            $suppliers = $this->supplierRepository->getListing(['ids' => $supplierIds], ['id', 'name'])->keyBy('id');
        }

        return ListContractResource::make($data)
            ->additional([
                'suppliers' => $suppliers,
            ])->resolve();
    }

    public function deleteContractById($id)
    {
        $contract = $this->contractRepository->find($id);
        if (empty($contract)) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2028,
            ];
        }

        $deleteContract = $contract->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
        if (!$deleteContract) {
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_2030,
            ];
        }

        DB::beginTransaction();
        try {
            $deleteContractMonitor = $this->contractMonitorRepository->deleteByContractIds($id);
            if (!$deleteContractMonitor) {
                DB::rollBack();
                return [
                  'success' => false,
                  'error_code' => AppErrorCode::CODE_2029,
                ];
            }

            $this->contractFileRepository->deleteByContractIds($id);
            $this->contractPaymentRepository->deleteByContractIds($id);
            $this->contractAppendixRepository->deleteByContractIds($id);

            DB::commit();
        } catch (\Throwable $exception) {
            dd($exception);
            DB::rollBack();
            return [
                'success' => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
