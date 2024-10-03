<?php

namespace App\Repositories;

use App\Models\ContractPayment;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ContractPaymentRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractPayment::class;
    }

    public function deleteByContractIds($contractIds)
    {
        return $this->_model->whereIn('contract_id', Arr::wrap($contractIds))->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function updateOrInsertContractPayment($payment, $contractId)
    {
        return $this->_model->updateOrInsert([
            'order' => $payment['order'],
            'contract_id' => $contractId
        ], [
           'order' => $payment['order'],
           'payment_date' => $payment['payment_date'],
           'money' => $payment['money'],
           'description' => $payment['description'],
        ]);
    }
}
