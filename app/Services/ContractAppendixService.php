<?php

namespace App\Services;

use App\Http\Resources\ListContractAppendixResource;
use App\Repositories\ContractAppendixRepository;

class ContractAppendixService
{
    public function __construct(
        protected ContractAppendixRepository $contractAppendixRepository,
    ) {

    }

    public function getListContractAppendix(array $filters = [])
    {
        $data = $this->contractAppendixRepository->getListing($filters, with: ['contract:id,code,name']);
        if ($data->isEmpty()) {
            return [];
        }

        return ListContractAppendixResource::make($data)->resolve();
    }
}
