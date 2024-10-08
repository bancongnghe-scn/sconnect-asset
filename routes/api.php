<?php

use App\Http\Controllers\AssetTypeGroupController;
use App\Http\Controllers\ContractController;
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
        'asset-type'        => App\Http\Controllers\AssetTypeController::class,
        'asset-type-group'  => AssetTypeGroupController::class,
        'industry'          => App\Http\Controllers\industryController::class,
        'supplier'          => App\Http\Controllers\SupplierController::class,
        'contract'          => ContractController::class,
        'contract-appendix' => App\Http\Controllers\ContractAppendixController::class,
    ]);

    Route::prefix('asset-type')->controller(App\Http\Controllers\AssetTypeController::class)
        ->group(function () {
            Route::post('/delete-multiple', 'deleteMultiple');
        });

    Route::post('contract/{id}', [ContractController::class, 'update']);
    Route::post('contract-appendix/{id}', [App\Http\Controllers\ContractAppendixController::class, 'update']);
});
