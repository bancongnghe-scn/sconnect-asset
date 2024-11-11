<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/ping', function () {
    event(new App\Events\ShoppingPlanCommentEvent(36, 'Nguyen Van Hoang', 'Xin chao', '11/09/2024 14:36'));

    return 'pong';
});

Route::middleware(['authenSSO'])->group(function () {
    Route::get('authen', function () {});
});

Route::middleware(['checkAuth'])->group(function () {
    Route::prefix('rbac')->group(function () {
        Route::view('role/list', 'rbac.role.list');
        Route::view('permission/list', 'rbac.permission.list');
        Route::view('menu/list', 'rbac.menu.list');
    });

    Route::view('/', 'home')->name('home');
    Route::view('asset-type-group/list', 'assets.asset_type_groups.list')->name('asset.type-group.list');
    Route::view('asset-type/list', 'assets.asset_type.list');
    Route::view('industry/list', 'assets.industry.list');
    Route::view('supplier/list', 'assets.supplier.list');
    Route::view('contract/list', 'assets.contract.listContractAndAppendix');
    Route::prefix('shopping-plan-company')->group(function () {
        Route::prefix('year')->group(function () {
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompanyYearController::class, 'index']);
            Route::view('update/{id}', 'assets.shopping-plan-company.year.update');
            Route::view('view/{id}', 'assets.shopping-plan-company.year.detail');
        });
    });
    Route::prefix('shopping-plan-organization')->group(function () {
        Route::prefix('year')->group(function () {
            Route::view('register/{id}', 'assets.shopping-plan-organization.year.register');
            Route::view('view/{id}', 'assets.shopping-plan-organization.year.detail');
        });
    });
});
