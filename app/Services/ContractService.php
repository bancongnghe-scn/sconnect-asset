<?php

namespace App\Services;

use App\Models\Contract;
use App\Repositories\ContractFileRepository;
use App\Repositories\ContractMonitorRepository;
use App\Repositories\ContractPaymentRepository;
use App\Repositories\ContractRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractService
{
    public function __construct(
        protected ContractRepository $contractRepository,
        protected ContractMonitorRepository $contractMonitorRepository,
        protected ContractFileRepository $contractFileRepository,
        protected ContractPaymentRepository $contractPaymentRepository
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
                $insertContractPayments = resolve(ContractPaymentService::class)->createMultipleContractPayment($payments, $contract->id);
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
            dd($exception);
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
}
