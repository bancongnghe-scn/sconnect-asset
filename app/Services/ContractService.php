<?php

namespace App\Services;

use App\Http\Resources\ContractInfoResource;
use App\Http\Resources\ListContractResource;
use App\Models\Contract;
use App\Models\Monitor;
use App\Repositories\ContractAppendixRepository;
use App\Repositories\ContractFileRepository;
use App\Repositories\MonitorRepository;
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
        protected MonitorRepository $contractMonitorRepository,
        protected ContractFileRepository $contractFileRepository,
        protected ContractPaymentRepository $contractPaymentRepository,
        protected SupplierRepository $supplierRepository,
        protected ContractAppendixRepository $contractAppendixRepository,
    ) {

    }

    public function createContract($data)
    {
        $contract = $this->contractRepository->getFirst(['code' => $data['code']]);
        if (!empty($contract)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2023,
            ];
        }

        $data['created_by'] = Auth::id();
        $data['status']     = Contract::STATUS_PENDING;
        $contract           = $this->contractRepository->create($data);

        DB::beginTransaction();
        try {
            $insertContractMonitor = resolve(MonitorService::class)->insertMonitors($data['user_ids'], $contract->id);
            if (!$insertContractMonitor) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2025,
                ];
            }

            $files = $data['files'] ?? [];
            if (!empty($files)) {
                $insertContractFiles = resolve(ContractFileService::class)->insertContractFiles($files, $contract->id);
                if (!$insertContractFiles) {
                    DB::rollBack();

                    return [
                        'success'    => false,
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
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2027,
                    ];
                }
            }

            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
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
        $suppliers   = [];
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
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2028,
            ];
        }

        $deleteContract = $contract->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
        if (!$deleteContract) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2030,
            ];
        }

        DB::beginTransaction();
        try {
            $deleteContractMonitor = $this->contractMonitorRepository->deleteMonitor([
                'target_id' => $id,
                'type'      => Monitor::TYPE_CONTRACT,
            ]);
            if (!$deleteContractMonitor) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2029,
                ];
            }

            $this->contractFileRepository->deleteByContractIds($id);
            $this->contractPaymentRepository->deleteByContractIds($id);
            $this->contractAppendixRepository->deleteByContractIds($id);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateContract($data, $id)
    {
        $contract = $this->contractRepository->find($id);
        if (empty($contract)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2028,
            ];
        }

        $contract->fill($data);
        if (!$contract->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2031,
            ];
        }

        DB::beginTransaction();
        try {
            $updateContractMonitor = resolve(MonitorService::class)->updateMonitor($id, $data['user_ids']);
            if (!$updateContractMonitor) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2032,
                ];
            }

            $payments = $data['payments'] ?? [];
            if (!empty($payments)) {
                $order = 1;
                foreach ($payments as $payment) {
                    $payment['order']      = $order++;
                    $updateContractPayment = $this->contractPaymentRepository->updateOrInsertContractPayment($payment, $id);
                    if (!$updateContractPayment) {
                        DB::rollBack();

                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2027,
                        ];
                    }
                }
            }

            $files = $data['files'] ?? [];
            if (!empty($files)) {
                $updateContractFile = resolve(ContractFileService::class)->updateContractFiles($files, $id);
                if (!$updateContractFile) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2026,
                    ];
                }
            }
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function findContract($id)
    {
        $contract = $this->contractRepository->getFirst(
            ['id' => $id],
            with: [
                'contractFiles:id,contract_id,file_url,file_name',
                'contractPayments:id,contract_id,order,payment_date,money,description',
                'contractAppendixApproval:id,code,name,signing_date,from,description,contract_id',
                'contractMonitors',
            ]
        );

        if (empty($contract)) {
            return [];
        }

        return ContractInfoResource::make($contract)->resolve();
    }

    public function deleteContractMultiple($ids)
    {
        DB::beginTransaction();
        try {
            $this->contractRepository->deleteMultipleByIds($ids);

            $this->contractMonitorRepository->deleteMonitor([
                'target_id' => $ids,
                'type'      => Monitor::TYPE_CONTRACT,
            ]);

            $this->contractFileRepository->deleteByContractIds($ids);
            $this->contractPaymentRepository->deleteByContractIds($ids);
            $this->contractAppendixRepository->deleteByContractIds($ids);

            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
