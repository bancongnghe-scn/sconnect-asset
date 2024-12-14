<?php

use App\Http\Controllers\ReportController;
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

Route::middleware(['web'])->group(function () {
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
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompanyYearController::class, 'index']);
            Route::view('update/{id}', 'assets.shopping-plan-company.year.update');
            Route::view('view/{id}', 'assets.shopping-plan-company.year.detail');
        });
    });
    Route::prefix('shopping-plan-organization')->group(function () {
        Route::prefix('year')->group(function () {
            Route::view('register/{id}', 'assets.shopping_plan_organization.year.register');
            Route::view('view/{id}', 'assets.shopping_plan_organization.year.detail');
        });
    });
});

Route::prefix('report')->group(function () {
    Route::get('/overview-report', [ReportController::class, 'overviewReport'])->name('assets.report.overviewReport');
    Route::get('/value-report', function () {
        return view('assets.report.valueReport');
    })->name('assets.report.valueReport');

    Route::get('/operating-cost-report', function () {
        return view('assets.report.operatingReport');
    })->name('assets.report.operatingReport');

    Route::get('/use-report', function () {
        return view('assets.report.useReport');
    })->name('assets.report.useReport');

    Route::get('/maintain-report', function () {
        return view('assets.report.maintainReport');
    })->name('assets.report.maintainReport');

    Route::get('/buy-report', function () {
        return view('assets.report.buyReport');
    })->name('assets.report.buyReport');

    Route::get('/supplier-report', function () {
        return view('assets.report.supplierReport');
    })->name('assets.report.supplierReport');
});
