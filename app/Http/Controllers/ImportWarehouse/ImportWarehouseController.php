<?php

namespace App\Http\Controllers\ImportWarehouse;

use App\Exports\ImportWarehouseExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateImportWarehouseRequest;
use App\Services\ImportWarehouseService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getListImportWarehouse(Request $request)
    {
        $request->validate([
            'code_name'  => 'nullable|string',
            'status'     => 'nullable|integer',
            'user_id'    => 'nullable|integer',
            'created_at' => 'nullable|date',
            'page'       => 'nullable|integer',
            'limit'      => 'nullable|integer',
        ]);

        try {
            $result = $this->importWarehouseService->getListImportWarehouse($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getInfoImportWarehouse(string $id)
    {
        try {
            $result = $this->importWarehouseService->getInfoImportWarehouse($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
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
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function completeImportWarehouse(string $id)
    {
        try {
            $result = $this->importWarehouseService->completeImportWarehouse($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updateImportWarehouse(string $id, CreateImportWarehouseRequest $request)
    {
        try {
            $result = $this->importWarehouseService->updateImportWarehouse($id, $request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deleteImportWarehouse(string $id)
    {
        try {
            $result = $this->importWarehouseService->deleteImportWarehouse($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function exportImportWarehouse(Request $request)
    {
        $request->validate([
            'ids'   => 'nullable|array',
            'ids.*' => 'integer',
        ]);

        $id = $request->ids ?? [];

        return Excel::download(new ImportWarehouseExport($id), 'phieu_nhap_kho.xlsx');
    }
}
