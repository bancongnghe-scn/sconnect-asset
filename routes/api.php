<?php

use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AssetTypeGroupController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ImportWarehouse\ImportWarehouseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Rbac\RoleController;
use App\Http\Controllers\ShoppingAssetOrderController;
use App\Http\Controllers\ShoppingPlanOrganization\ShoppingPlanOrganizationYearController;
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
    return Maatwebsite\Excel\Facades\Excel::download(new App\Exports\OrderExport(), 'purchase_proposal.xlsx');

    return 'pong';
});

Route::middleware('checkAuth')->group(function () {
    Route::resources([
        'asset-type'        => AssetTypeController::class,
        'asset-type-group'  => AssetTypeGroupController::class,
        'industry'          => App\Http\Controllers\IndustryController::class,
        'supplier'          => App\Http\Controllers\SupplierController::class,
        'contract'          => ContractController::class,
        'contract-appendix' => App\Http\Controllers\ContractAppendixController::class,
        'user'              => App\Http\Controllers\UserController::class,
    ]);


    Route::prefix('rbac')->group(function () {
        Route::prefix('menu')->controller(App\Http\Controllers\Rbac\MenuController::class)->group(function () {
            Route::get('user', 'getMenuUserLogin');
            Route::get('parent', 'getMenuParent');
        });

        Route::resources([
            'role'       => RoleController::class,
            'permission' => App\Http\Controllers\Rbac\PermissionController::class,
            'menu'       => App\Http\Controllers\Rbac\MenuController::class,
        ]);
    });

    Route::prefix('shopping-plan-company')->group(function () {
        Route::controller(App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyController::class)->group(function () {
            Route::get('show/{id}', 'findShoppingPlanCompany');
            Route::post('sent-notification-register', 'sentNotificationRegister');
            Route::get('send-accountant-approval/{id}', 'sendAccountantApproval');
            Route::get('send-manager-approval/{id}', 'sendManagerApproval');
            Route::post('manager-approval', 'managerApproval');
            Route::delete('delete/{id}', 'deleteShoppingPlanCompany');
            Route::get('list', 'getListShoppingPlan');
            Route::get('get-organization-register/{id}', 'getOrganizationRegister');
        });

        Route::prefix('year')->controller(App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyYearController::class)->group(function () {
            Route::get('list', 'getListShoppingPlanCompanyYear');
            Route::post('create', 'createShoppingPlanCompanyYear');
            Route::put('update/{id}', 'updateShoppingPlanCompanyYear');
        });

        Route::prefix('quarter')->controller(App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyQuarterController::class)->group(function () {
            Route::get('list', 'getListShoppingPlanCompanyQuarter');
            Route::post('create', 'createShoppingPlanCompanyQuarter');
            Route::put('update/{id}', 'updateShoppingPlanCompanyQuarter');
        });

        Route::prefix('week')->controller(App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyWeekController::class)->group(function () {
            Route::get('list', 'getListShoppingPlanCompanyWeek');
            Route::post('create', 'createShoppingPlanCompanyWeek');
            Route::put('update/{id}', 'updateShoppingPlanCompanyWeek');
            Route::get('handle-shopping/{id}', 'handleShopping');
            Route::post('synthetic-shopping', 'syntheticShopping');
            Route::post('send-approval', 'sendApprovalWeek');
            Route::get('supplier/{id}', 'getSupplierOfShoppingPlanWeek');
            Route::get('complete/{id}', 'completeShoppingPlanWeek');
        });
    });

    Route::prefix('shopping-plan-organization')->group(function () {
        Route::controller(App\Http\Controllers\ShoppingPlanOrganization\ShoppingPlanOrganizationController::class)->group(function () {
            Route::get('view/{id}', 'findShoppingPlanOrganization');
            Route::get('get-register/{id}', 'getRegisterShoppingPlanOrganization');
            Route::post('account-approval', 'accountApprovalShoppingPlanOrganization');
            Route::post('review', 'saveTotalAssetApproval');
        });

        Route::prefix('year')->controller(ShoppingPlanOrganizationYearController::class)->group(function () {
            Route::get('list', 'getListShoppingPlanOrganizationYear');
            Route::post('register', 'registerShoppingPlanOrganizationYear');
        });

        Route::prefix('quarter')->controller(App\Http\Controllers\ShoppingPlanOrganization\ShoppingPlanOrganizationQuarterController::class)->group(function () {
            Route::get('list', 'getListShoppingPlanOrganizationQuarter');
            Route::post('register', 'registerShoppingPlanOrganizationQuarter');
        });

        Route::prefix('week')->controller(App\Http\Controllers\ShoppingPlanOrganization\ShoppingPlanOrganizationWeekController::class)->group(function () {
            Route::get('list', 'getListShoppingPlanOrganizationWeek');
            Route::post('register', 'registerShoppingPlanOrganizationWeek');
        });
    });

    Route::prefix('shopping-plan-log')->controller(App\Http\Controllers\ShoppingPlanLogController::class)->group(function () {
        Route::get('get-by-id/{id}', 'getShoppingPlanLogByRecordId');
    });

    Route::prefix('comment')->controller(App\Http\Controllers\CommentController::class)->group(function () {
        Route::get('list', 'getListComment');
        Route::post('sent', 'sentComment');
        Route::post('delete/{id}', 'deleteComment');
        Route::post('edit', 'editComment');
    });

    Route::prefix('delete-multiple')->group(function () {
        Route::post('asset-type', [AssetTypeController::class, 'deleteMultiple']);
        Route::post('asset-type-group', [AssetTypeGroupController::class, 'deleteMultiple']);
        Route::post('industry', [App\Http\Controllers\IndustryController::class, 'deleteMultiple']);
        Route::post('supplier', [App\Http\Controllers\SupplierController::class, 'deleteMultiple']);
        Route::post('contract', [ContractController::class, 'deleteMultiple']);
        Route::post('appendix', [App\Http\Controllers\ContractAppendixController::class, 'deleteMultiple']);
        Route::post('shopping-plan-company', [App\Http\Controllers\ShoppingPlanCompany\ShoppingPlanCompanyController::class, 'deleteMultiple']);
    });

    Route::post('contract/{id}', [ContractController::class, 'update']);

    Route::post('contract-appendix/{id}', [App\Http\Controllers\ContractAppendixController::class, 'update']);

    Route::get('getAllJob', [App\Http\Controllers\JobTitleController::class, 'getAllJob']);

    Route::prefix('cache')->controller(App\Http\Controllers\CachingController::class)->group(function () {
        Route::post('flush', 'flushCache');
        Route::post('add', 'addCache');
        Route::get('get', 'getCache');
    });

    Route::prefix('manage-asset-lost')->controller(App\Http\Controllers\Manage\AssetLostController::class)->group(function () {
        Route::get('list', 'getListAssetLost');
        Route::get('{id}', 'findAssetLost');
        Route::post('update', 'updateAssetLost');
    });

    Route::get('manage-asset-cancel', [App\Http\Controllers\Manage\AssetCancelController::class, 'getListAssetCancel']);
    Route::get('manage-asset-liquidation', [App\Http\Controllers\Manage\AssetLiquidationController::class, 'getListAssetLiquidation']);

    Route::prefix('manage-plan-liquidation')->controller(App\Http\Controllers\Manage\PlanLiquidationController::class)->group(function () {
        Route::post('create', 'createPlan');
        Route::get('get', 'getPlan');
        Route::get('detail/{id}', 'detail');
        Route::post('update-asset', 'updateAssetToPlan');
        Route::delete('delete-asset/{plan_id}', 'deleteAssetFromPlan');
        Route::post('delete-multi', 'deleteMultiPlan');
        Route::post('update-status-asset', 'changeStatusAssetOfPlan');
        Route::post('update-status-multi-asset', 'changeStatusMultiAssetOfPlan');
        Route::post('update-plan/{id}', 'updatePlan');
    });

    Route::prefix('asset-damaged')->controller(App\Http\Controllers\Inventory\AssetDamagedController::class)->group(function () {
        Route::get('list', 'getListAssetDamaged');
        Route::post('update-asset-liquidation', 'updateMultiAssetLiquidation');
        Route::post('update-asset-cancel', 'updateMultiAssetCancel');
    });

    Route::prefix('asset-repair')->controller(App\Http\Controllers\Inventory\AssetRepairController::class)->group(function () {
        Route::post('create', 'createAssetRepair');
        Route::get('list', 'getListAssetRepair');
        Route::get('detail/{id}', 'getAssetRepair');
        Route::post('multi', 'getMultiAssetRepair');
        Route::post('update/multi', 'updateMultiAssetRepaired');
    });

    Route::prefix('shopping-asset')->controller(App\Http\Controllers\ShoppingAssetController::class)->group(function () {
        Route::post('sent-info', 'sentInfoShoppingAsset');
        Route::post('approval', 'approvalShoppingAsset');
        Route::get('list', 'getListShoppingAsset');
    });

    Route::prefix('order')->controller(App\Http\Controllers\OrderController::class)->group(function () {
        Route::get('list', 'getListOrder');
        Route::post('create', 'createOrder');
        Route::post('update', 'updateOrder');
        Route::get('find/{id}', 'findOrder');
        Route::post('delete', 'deleteOrder');
    });

    Route::prefix('shopping-asset-order')->controller(ShoppingAssetOrderController::class)->group(function () {
        Route::get('list', 'getListShoppingAssetOrder');
    });

    Route::prefix('import-warehouse')->controller(ImportWarehouseController::class)->group(function () {
        Route::get('asset', 'getAssetForImportWarehouse');
        Route::post('create', 'createImportWarehouse');
        Route::get('list', 'getListImportWarehouse');
        Route::get('info/{id}', 'getInfoImportWarehouse');
        Route::get('complete/{id}', 'completeImportWarehouse');
        Route::post('update/{id}', 'updateImportWarehouse');
        Route::get('delete/{id}', 'deleteImportWarehouse');
        Route::get('export', 'exportImportWarehouse');
    });
});

Route::prefix('report')->group(function () {
    Route::get('/get-data-value-report', [ReportController::class, 'getDataValueReport'])->name('assets.report.getDataValueReport');

    Route::get('/get-data-operating-report', [ReportController::class, 'getDataOperatingReport'])->name('assets.report.getDataOperatingReport');

    Route::get('/get-data-structure-report', [ReportController::class, 'getDataStructureReport'])->name('assets.report.getDataStructureReport');

    Route::get('/get-data-use-report', [ReportController::class, 'getDataUseReport'])->name('assets.report.getDataUseReport');

    Route::get('/get-data-maintain-report', [ReportController::class, 'getDataMaintainReport'])->name('assets.report.getDataMaintainReport');
});
