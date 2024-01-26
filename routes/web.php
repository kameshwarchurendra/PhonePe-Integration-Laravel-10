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
use App\Http\Controllers\PhonepayPaymentController;

Route::get('/', [PhonepayPaymentController::class,'index']);

Route::get('pay-phonepay', [PhonepayPaymentController::class,'payWithphonepay'])->name('pay-phonepay');
Route::any('phonepay-status/{order_id}', [PhonepayPaymentController::class,'getPaymentStatus'])->name('phonepay-status');

Route::get('payment-success', [PhonepayPaymentController::class,'success'])->name('payment-success');
Route::get('payment-fail', [PhonepayPaymentController::class,'fail'])->name('payment-fail');
