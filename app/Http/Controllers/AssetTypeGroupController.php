<?php

namespace App\Http\Controllers;

use App\Services\AssetTypeGroupService;
use Illuminate\Http\Request;

class AssetTypeGroupController extends Controller
{
    public function __construct(
        protected AssetTypeGroupService $assetTypeGroupService,
    ) {

    }

    public function index(Request $request)
    {
        $request->validate([
            'id'    => 'integer',
            'name'  => 'nullable|string|min:2|max:255',
            'page'  => 'nullable|integer',
            'limit' => 'nullable|integer|max:200',
        ]);

        try {
            $result = $this->assetTypeGroupService->getListAssetTypeGroup($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
            ]
        );

        try {
            $result = $this->assetTypeGroupService->createAssetTypeGroup($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->assetTypeGroupService->deleteAssetTypeGroup($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
            ]
        );

        try {
            $result = $this->assetTypeGroupService->updateAssetTypeGroup($request->all(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function show(string $id)
    {
        try {
            $result = $this->assetTypeGroupService->findAssetTypeGroup($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
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
            $result = $this->assetTypeGroupService->deleteAssetTypeGroupMultiple($request->get('ids'));

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
