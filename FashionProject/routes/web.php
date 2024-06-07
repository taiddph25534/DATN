<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoucherController;
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
Route::get('vouchers', [VoucherController::class, 'index']);
Route::post('addvouchers', [VoucherController::class, 'store']); 
Route::get('/', function () {
    return view('welcome');
});
