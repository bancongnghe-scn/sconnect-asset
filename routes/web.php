<?php

use App\Http\Controllers\Auth\LoginController;
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
Route::controller(\App\Http\Controllers\Auth\LoginSSOController::class)->group(function () {
    Route::get('/', 'loginSSO');
    Route::get('/login', 'login')->name('login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('asset-type-group')->group(function (){
    Route::view('/list', 'assets.asset_type_groups.list');
});
