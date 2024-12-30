<?php

namespace App\Http\Controllers;

use App\Services\AssetTypeService;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    public function __construct(
        protected AssetTypeService $assetTypeService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'name'                   => 'nullable|string|max:255',
            'asset_type_group_id'    => 'nullable|integer',
            'page'                   => 'nullable|integer',
            'limit'                  => 'nullable|integer|max:200',
        ]);

        try {
            $result = $this->assetTypeService->getListAssetType($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'asset_type_group_id' => 'required|integer',
            'maintenance_months'  => 'required|integer',
            'measure'             => 'required|integer',
            'description'         => 'nullable|string',
        ]);

        try {
            $result = $this->assetTypeService->createAssetType($request->all());

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
            $result = $this->assetTypeService->findAssetType($id);

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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'asset_type_group_id' => 'required|integer',
            'maintenance_months'  => 'required|integer',
            'measure'             => 'required|integer',
            'description'         => 'nullable|string',
        ]);

        try {
            $result = $this->assetTypeService->updateAssetType($request->all(), $id);

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
            $result = $this->assetTypeService->deleteAssetTypeById($id);
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
            $result = $this->assetTypeService->deleteMultipleByIds($request->get('ids'));
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
