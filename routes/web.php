<?php

use App\Exports\ReportExport;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
        });
        Route::prefix('quarter')->group(function () {
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyQuarterController::class, 'index']);
        });
        Route::prefix('week')->group(function () {
            Route::get('list', [App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyWeekController::class, 'index']);
        });
    });

    Route::prefix('order')->group(function () {
        Route::view('list', 'assets.order.order_list');
        Route::view('detail/{id}', 'assets.order.order_detail');
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
    Route::prefix('allocation-rate')->group(function () {
        Route::view('list', 'assets.allocation_rate.list');
    });
    Route::prefix('maintain')->group(function () {
        Route::view('list', 'assets.maintain.list');
    });
    Route::prefix('plan-inventory')->group(function () {
        Route::view('list', 'assets.plan-inventory.list');
    });
    Route::view('summernote', 'common.summernote.summernote_comment');

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

    Route::prefix('asset')->group(function () {
        Route::get('/list-asset', [AssetController::class, 'listAsset'])->name('assets.listAsset');
        Route::get('/list-user-asset', [AssetController::class, 'listUserAsset'])->name('assets.listUserAsset');
        Route::get('/list-organization-asset', [AssetController::class, 'listOrgAsset'])->name('assets.listOrgAsset');
        Route::get('/export-list-asset', [AssetController::class, 'exportListAsset'])->name('assets.exportListAsset');
    });
});

Route::get('/xslt-transform', [AssetController::class, 'transformXmlToHtml']);
Route::get('/excel-transform', function () {
    return Excel::download(new ReportExport(), 'output.xlsx');
});
Route::view('asset/info/{id}', 'assets.asset.info_qr');
