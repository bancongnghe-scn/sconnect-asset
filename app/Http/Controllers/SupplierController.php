<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService,
    ) {

    }

    public function index(Request $request)
    {
        $request->validate([
            'code_name'      => 'nullable|string|max:255',
            'industry_ids'   => 'nullable|array',
            'industry_ids.*' => 'integer',
            'status'         => 'nullable|array',
            'status.*'       => 'integer',
            'page'           => 'integer',
            'limit'          => 'integer|max:200',
        ]);

        try {
            $result = $this->supplierService->getListSupplier($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        try {
            $result = $this->supplierService->createSupplier($request->validated());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = $this->supplierService->findSupplier($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSupplierRequest $request, string $id)
    {
        try {
            $result = $this->supplierService->updateSupplier($request->validated(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->supplierService->deleteSupplierById($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
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
            $result = $this->supplierService->deleteSupplierMultiple($request->get('ids'));

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
