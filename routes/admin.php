<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::name('admin.')->prefix('v1')->group(
    function () {
        Route::post('login', [LoginController::class, 'login'])->name('login');

        Route::group(
            ['middleware' => ['auth:admin']], function () {
                Route::apiResource('transactions', TransactionController::class)->only('store');
                Route::apiResource('transactions.payments', PaymentController::class)->only('store');
            }
        );
    }
);
