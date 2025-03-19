<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePlanInventoryRequest;
use App\Http\Requests\UpdatePlanInventoryRequest;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
    ) {
    }

    public function getPlanInventory(Request $request)
    {
        $request->validate([
            'name'       => 'nullable|string',
            'start_time' => 'nullable|date|date_format:Y-m-d',
            'end_time'   => 'nullable|date|date_format:Y-m-d',
            'status'     => 'nullable|integer',
            'page'       => 'nullable|integer',
            'limit'      => 'nullable|integer',
        ]);

        try {
            $result = $this->inventoryService->getPlanInventory($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function createPlanInventory(CreatePlanInventoryRequest $request)
    {
        try {
            $result = $this->inventoryService->createPlanInventory($request->validated());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function findPlanInventory($id)
    {
        try {
            $result = $this->inventoryService->findPlanInventory($id);
            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function startPlanInventory($id)
    {
        try {
            $result = $this->inventoryService->startPlanInventory($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updatePlanInventory($id, UpdatePlanInventoryRequest $request)
    {
        try {
            $result = $this->inventoryService->updatePlanInventory($id, $request->validated());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
