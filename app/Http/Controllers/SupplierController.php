<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    )
    {

    }

    public function index(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'industry_id' => 'nullable|integer',
            'level' => 'nullable|integer',
            'page' => 'integer',
            'limit' => 'integer|max:200',
        ]);

        try {
            $result = $this->supplierService->getListSupplier($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'contact' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string',
            'industry_ids' => 'required|array',
            'industry_ids.*' => 'integer',
            'asset_type_ids' => 'required|array',
            'asset_type_ids.*' => 'integer',
            'description' => 'nullable|string',
            'meta_data' => 'nullable|array',
        ]);

        try {
            $result = $this->supplierService->createSupplier($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
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
            return response_error();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $result = $this->supplierService->updateSupplier($request->all(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
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
            return response_error();
        }
    }
}
