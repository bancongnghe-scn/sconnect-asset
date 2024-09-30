<?php

namespace App\Repositories;

use App\Models\ContractPayment;
use App\Repositories\Base\BaseRepository;

class ContractPaymentRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ContractPayment::class;
    }
}
