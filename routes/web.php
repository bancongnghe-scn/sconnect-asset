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
    return 'pong';
});


Route::middleware(['authenSSO'])->group(function () {
    Route::get('authen', function () {});
});

Route::middleware('checkAuth')->group(function () {
    Route::get('/login/{id}', function ($id) {
        Illuminate\Support\Facades\Auth::loginUsingId($id);

        return redirect('/');
    })->name('login');

    Route::get('/logout', [App\Http\Controllers\Auth\LoginSSOController::class, 'logout']);

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
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyYearController::class, 'index']);
            Route::view('update/{id}', 'assets.shopping-plan-company.year.update');
            Route::view('view/{id}', 'assets.shopping-plan-company.year.detail');
        });
        Route::prefix('quarter')->group(function () {
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyQuarterController::class, 'index']);
            Route::view('update/{id}', 'assets.shopping-plan-company.quarter.update');
            Route::view('view/{id}', 'assets.shopping-plan-company.quarter.detail');
        });
        Route::prefix('week')->group(function () {
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyWeekController::class, 'index']);
            Route::view('update/{id}', 'assets.shopping-plan-company.week.update');
            Route::view('view/{id}', 'assets.shopping-plan-company.week.detail');
        });
    });
    Route::prefix('shopping-plan-organization')->group(function () {
        Route::prefix('year')->group(function () {
            Route::view('register/{id}', 'assets.shopping_plan_organization.year.register');
            Route::view('view/{id}', 'assets.shopping_plan_organization.year.detail');
        });

        Route::prefix('quarter')->group(function () {
            Route::view('register/{id}', 'assets.shopping_plan_organization.quarter.register');
            Route::view('view/{id}', 'assets.shopping_plan_organization.quarter.detail');
        });

        Route::prefix('week')->group(function () {
            Route::view('register/{id}', 'assets.shopping_plan_organization.week.register');
            Route::view('view/{id}', 'assets.shopping_plan_organization.week.detail');
        });
    });
    Route::prefix('order')->group(function () {
        Route::view('list', 'assets.order.list');
    });
    Route::prefix('import-warehouse')->group(function () {
        Route::view('list', 'assets.import_warehouse.list');
    });
    Route::prefix('cache')->group(function () {
        Route::get('key', function () {
            $key = config('cache_keys.keys.menu_key').Illuminate\Support\Facades\Auth::id();
            dd(Illuminate\Support\Facades\Cache::forget($key));
        });
        Route::get('tag', function () {
            dd(Illuminate\Support\Facades\Cache::tags(config('cache_keys.tags.menu_tag'))->clear());
        });
    });
    Route::view('/assets/manage/list', 'assets.manage.list')->name('assets.manage.list');
    Route::view('/assets/inventory/list', 'assets.inventory.list');
});

Route::prefix('asset')->group(function () {
    Route::view('info/{id}', 'assets.assets.info');
});
