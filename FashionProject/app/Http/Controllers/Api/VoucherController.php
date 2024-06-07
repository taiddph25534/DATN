<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return response()->json($vouchers,200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:vouchers',
            'discountType' => 'required|string',
            'discountValue' => 'required|numeric',
            'expiryDate' => 'required|date',
            'minPurchaseAmount' => 'required|numeric',
            'pointRequired' => 'required|integer',
        ]);

        $voucher = Voucher::create($request->all());
        return response()->json($voucher, 201);
    }

    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return response()->json($voucher);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'string|max:255|unique:vouchers,code,' . $id,
            'discountType' => 'string',
            'discountValue' => 'numeric',
            'expiryDate' => 'date',
            'minPurchaseAmount' => 'numeric',
            'pointRequired' => 'integer',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());
        return response()->json($voucher);
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return response()->json(null, 204);
    }
}

