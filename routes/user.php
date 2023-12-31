<?php

use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\TransactionController;
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
Route::name('user.')->prefix('v1')->group(
    function () {
        Route::post('login', [LoginController::class, 'login'])->name('login');

        Route::group(
            ['middleware' => ['auth:user']], function () {
                Route::get('transactions', [TransactionController::class, 'index']);
            }
        );
    }
);
