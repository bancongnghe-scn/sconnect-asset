<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexAppendixRequest;
use App\Http\Requests\StoreAppendixRequest;
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

    public function store(StoreAppendixRequest $request)
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

    public function show(string $id)
    {
        try {
            $result = $this->contractAppendixService->findAppendix($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function update(StoreAppendixRequest $request, string $id)
    {
        try {
            $result = $this->contractAppendixService->updateAppendix($request->validated(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->contractAppendixService->deleteAppendixById($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
