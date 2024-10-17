<?php

use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AssetTypeGroupController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('ping', function () {
    return 'pong';
});

Route::middleware('auth')->group(function () {
    Route::resources([
        'asset-type'        => AssetTypeController::class,
        'asset-type-group'  => AssetTypeGroupController::class,
        'industry'          => App\Http\Controllers\IndustryController::class,
        'supplier'          => App\Http\Controllers\SupplierController::class,
        'contract'          => ContractController::class,
        'contract-appendix' => App\Http\Controllers\ContractAppendixController::class,
        'user'              => App\Http\Controllers\UserController::class,
        'shopping-plan'     => App\Http\Controllers\ShoppingPlanCompanyController::class,
    ]);


    Route::prefix('rbac')->group(function () {
        Route::prefix('menu')->controller(App\Http\Controllers\MenuController::class)->group(function () {
            Route::get('user', 'getMenuUserLogin');
            Route::get('parent', 'getMenuParent');
        });

        Route::resources([
            'role'       => RoleController::class,
            'permission' => App\Http\Controllers\PermissionController::class,
            'menu'       => App\Http\Controllers\MenuController::class,
        ]);
    });

    Route::prefix('/delete-multiple')->group(function () {
        Route::post('asset-type', [AssetTypeController::class, 'deleteMultiple']);
        Route::post('asset-type-group', [AssetTypeGroupController::class, 'deleteMultiple']);
        Route::post('industry', [\App\Http\Controllers\IndustryController::class, 'deleteMultiple']);
        Route::post('supplier', [\App\Http\Controllers\SupplierController::class, 'deleteMultiple']);
    });

    Route::post('contract/{id}', [ContractController::class, 'update']);
    Route::post('contract-appendix/{id}', [App\Http\Controllers\ContractAppendixController::class, 'update']);
});
