<?php

namespace App\Http;

use App\Http\Controllers\Controller;
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
            dd($exception);
            report($exception);

            return response_error();
        }
    }
}
