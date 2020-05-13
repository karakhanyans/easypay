<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Auth::routes();
Route::resources([
    'rates' => 'RateController',
]);
Route::middleware('auth')
    ->group(function () {
        Route::group(['prefix' => 'transactions'], function(){
            Route::get('/', [TransactionController::class, 'index'])->name('transactions');
            Route::get('download', [TransactionController::class, 'download'])->name('transactions.download');
        });
        Route::group(['prefix' => 'wallets/{wallet}/transaction'], function(){
            Route::get('/', [WalletController::class, 'transactionForm'])->name('wallets.transaction');
            Route::post('/', [WalletController::class, 'transaction'])->name('wallets.transaction.store');
        });
        Route::resources([
            'wallets' => 'WalletController'
        ]);
    });

