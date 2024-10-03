<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexAppendixRequest;
use App\Http\Requests\StoreContractRequest;
use App\Services\ContractAppendixService;

class ContractAppendixController extends Controller
{
    public function __construct(
        protected ContractAppendixService $contractAppendixService,
    ) {

    }

    public function index(IndexAppendixRequest $request)
    {
        try {
            $result = $this->contractAppendixService->getListContractAppendix($request->validated());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function store(StoreContractRequest $request)
    {
        try {
            $result = $this->contractAppendixService->createAppendix($request->validated());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
