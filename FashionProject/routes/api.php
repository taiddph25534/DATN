<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoucherController;


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
Route::get('vouchers', [VoucherController::class, 'index']); // Lấy danh sách voucher
Route::post('addvouchers', [VoucherController::class, 'store']); // Tạo mới voucher
Route::get('vouchers/{id}', [VoucherController::class, 'show']); // Lấy thông tin voucher theo id
Route::put('vouchers/{id}', [VoucherController::class, 'update']); // Cập nhật thông tin voucher theo id
Route::delete('vouchers/{id}', [VoucherController::class, 'destroy']); // Xóa voucher theo id

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
