<?php

namespace App\Http\Controllers\ImportWarehouse;

use App\Http\Controllers\Controller;
use App\Services\ImportWarehouseService;
use Illuminate\Http\Request;

class ImportWarehouseController extends Controller
{
    public function __construct(
        protected ImportWarehouseService $importWarehouseService,
    ) {

    }

    public function getAssetForImportWarehouse(Request $request)
    {
        $request->validate([
            'order_ids'   => 'required|array',
            'order_ids.*' => 'integer',
        ]);

        try {
            $result = $this->importWarehouseService->getAssetForImportWarehouse($request->get('order_ids'));

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
