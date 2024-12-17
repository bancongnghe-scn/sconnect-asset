<?php

namespace App\Http\Controllers\ImportWarehouse;

use App\Http\Controllers\Controller;
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
}
