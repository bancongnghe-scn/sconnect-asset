<?php

namespace App\Http\Controllers\ImportWarehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateImportWarehouseRequest;
use App\Services\ImportWarehouseService;

class ImportWarehouseController extends Controller
{
    public function __construct(
        protected ImportWarehouseService $importWarehouseService,
    ) {

    }

    public function getAssetForImportWarehouse(string $id)
    {
        try {
            $result = $this->importWarehouseService->getAssetForImportWarehouse($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function createImportWarehouse(CreateImportWarehouseRequest $request)
    {
        try {
            $result = $this->importWarehouseService->createImportWarehouse($request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            dd($exception);
            report($exception);

            return response_error();
        }
    }
}
