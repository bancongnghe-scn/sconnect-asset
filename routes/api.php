<?php

use App\Http\Controllers\AssetTypeGroupController;
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

Route::middleware('auth')->prefix('asset-type-group')
    ->controller(AssetTypeGroupController::class)
    ->group(function (){
    Route::get('/list', 'getListAssetTypeGroup')->name('asset.type_group.list');
    Route::post('/create', 'createAssetTypeGroup')->name('asset.type_group.create');
    Route::post('/delete', 'deleteAssetTypeGroup')->name('asset.type_group.delete');
    Route::post('/update', 'updateAssetTypeGroup')->name('asset.type_group.update');
});
