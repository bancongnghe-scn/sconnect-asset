<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Inventory\AssetRepairService;
use Illuminate\Support\Facades\Log;

class AssetRepairController extends Controller
{
    public function __construct(
        protected AssetRepairService $assetRepairService,
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function updateAssetRepair(Request $request)
    {
        $request->validate([
            'date_repair'               => 'nullable|date|date_format:Y-m-d',
            'date_repaired'             => 'nullable|date|date_format:Y-m-d',
            'location'                  => 'nullable|string',
            'supplier_id'               => 'nullable|integer',
            'performer_id'              => 'nullable|integer',
            'assets'                    => 'array',
            'assets.*.id'               => 'nullable|integer',
            'assets.*.cost_repair'      => 'nullable|integer',
            'assets.*.note_repair'      => 'nullable|string',
        ]);

        try {
            $result = $this->assetRepairService->updateAssetRepair($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function getListAssetRepair(Request $request)
    {

        try {
            $result = $this->assetRepairService->getListAssetRepair($request->all());

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function getAssetRepair(string $id)
    {
        try {
            $result = $this->assetRepairService->getAssetRepairById($id);

            return response_success($result);
        } catch (\Exception $e) {
            Log::error('message', $e->getMessage());
        }
    }

    public function updateMultiAssetRepaired(Request $request)
    {
        $request->validate([
            'date_repaired'                 => 'nullable|date|date_format:Y-m-d',
            'assets'                        => 'array',
            'assets.*.date_repair'          => 'nullable|date|date_format:Y-m-d',
            'assets.*.cost_repair'          => 'nullable|integer',
            'assets.*.note_repair'          => 'nullable|string',
            'assets.*.address_repair'       => 'nullable|integer',  // Tại công ty or nhà cung cấp
            'assets.*.address'              => 'nullable|string',
            'assets.*.performer_supplier'   => 'nullable|integer',  // Người thực hiện or Đơn vị sửa chữa
        ]);
        try {
            $result = $this->assetRepairService->updateMultiAssetRepaired($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Exception $e) {
            return response_error();
        }
    }

    public function getMultiAssetRepair(Request $request)
    {
        try {
            $result = $this->assetRepairService->getMultiAssetRepairById($request->asset_repair_ids);

            return response_success($result);
        } catch (\Exception $e) {
            return response_error();
        }
    }
}
