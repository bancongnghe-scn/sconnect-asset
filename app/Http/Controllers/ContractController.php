<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Services\ContractService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(
        protected ContractService $contractService,
    ) {

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
            report($exception);

            return response_error();
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'name_code'    => 'nullable|string|max:255',
            'type'         => 'nullable|array',
            'type.*'       => 'integer',
            'status'       => 'nullable|array',
            'status.*'     => 'integer',
            'signing_date' => 'nullable|date|date_format:Y-m-d',
            'from'         => 'nullable|date|date_format:Y-m-d',
            'page'         => 'nullable|integer',
            'limit'        => 'nullable|integer|max:200',
        ]);

        try {
            $result = $this->contractService->getListContract($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->contractService->deleteContractById($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function update(StoreContractRequest $request, string $id)
    {
        try {
            $result = $this->contractService->updateContract($request->validated(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function show(string $id)
    {
        try {
            $result = $this->contractService->findContract($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        try {
            $result = $this->contractService->deleteContractMultiple($request->get('ids'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
