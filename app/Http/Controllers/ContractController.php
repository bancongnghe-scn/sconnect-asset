<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Services\ContractService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(
        protected ContractService $contractService
    )
    {

    }

    public function store(StoreContractRequest $request)
    {
        try {
            $result = $this->contractService->createContract($request->validated());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
