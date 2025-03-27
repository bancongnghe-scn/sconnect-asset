<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePlanInventoryRequest;
use App\Http\Requests\UpdatePlanInventoryRequest;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Auth::user()->canPer('plan-inventory.view');

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
        Auth::user()->canPer('plan-inventory.create');

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
        Auth::user()->canPer('plan-inventory.view');

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
        Auth::user()->canPer('plan-inventory.start');

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
        Auth::user()->canPer('plan-inventory.update');

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

    public function completePlanInventory($id)
    {
        Auth::user()->canPer('plan-inventory.complete');

        try {
            $result = $this->inventoryService->completePlanInventory($id);
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deletePlanInventory(Request $request)
    {
        $request->validate([
            'id'       => 'required|integer',
        ]);

        Auth::user()->canPer('plan-inventory.delete');

        try {
            $result = $this->inventoryService->deletePlanInventory($request->get('id'));
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function uploadFileInventory(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
            'id'   => 'required|integer',
        ]);

        try {
            $result = $this->inventoryService->uploadFileInventory($request->file('file'), $request->get('id'));
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getListPlanInventoryUser(Request $request)
    {
        try {
            $result = $this->inventoryService->getListPlanInventoryUser($request->all());
            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getFileUploaded($id)
    {
        try {
            $result = $this->inventoryService->getFileUploaded($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
