<?php

namespace App\Services;

use App\Repositories\ContractPaymentRepository;
use Illuminate\Support\Facades\Auth;

class ContractPaymentService
{
    public function __construct(
        protected ContractPaymentRepository $contractPaymentRepository
    )
    {

    }

    public function createMultipleContractPayment($payments, $contractId)
    {
        $contractPayments = [];
        $order = 1;
        $userId = Auth::id();
        foreach ($payments as $payment) {
            $contractPayments[] = [
                'contract_id' => $contractId,
                'order' => $order ++,
                'payment_date' => $payment['payment_date'],
                'money' => $payment['money'],
                'description' => $payment['description'],
                'created_by' => $userId,
            ];
        }
        return $this->contractPaymentRepository->insert($contractPayments);
    }
}
