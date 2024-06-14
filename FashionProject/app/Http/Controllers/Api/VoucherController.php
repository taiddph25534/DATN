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
        return response()->json($vouchers, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:vouchers',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'expiry_date' => 'required|date',
            'min_purchase_amount' => 'required|numeric',
            'point_required' => 'required|integer',
            'max_usage' => 'integer|nullable',
            'category_id' => 'integer|nullable|exists:categories,id',
            'distribution_channels' => 'string|nullable',
            'created_count' => 'required|integer',
        ]);

        $voucher = Voucher::create($request->all());
        $voucher->remaining_count = $voucher->created_count;
        $voucher->save();
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
            'discount_type' => 'string',
            'discount_value' => 'numeric',
            'expiry_date' => 'date',
            'min_purchase_amount' => 'numeric',
            'point_required' => 'integer',
            'max_usage' => 'integer|nullable',
            'category_id' => 'integer|nullable|exists:categories,id',
            'distribution_channels' => 'string|nullable',
            'created_count' => 'integer|nullable',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());
        $voucher->remaining_count = $voucher->created_count - $voucher->used_count;
        $voucher->save();
        return response()->json($voucher);
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return response()->json(null, 204);
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'totalAmount' => 'required|numeric',
            'productIDs' => 'array'
        ]);

        $voucher = Voucher::where('code', $request->input('code'))->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        if ($request->input('totalAmount') < $voucher->min_purchase_amount) {
            return response()->json(['message' => 'Total amount is less than the minimum purchase amount required for this voucher'], 400);
        }

        if ($voucher->max_usage && $voucher->used_count >= $voucher->max_usage) {
            return response()->json(['message' => 'Voucher has been used the maximum number of times'], 400);
        }

        if ($voucher->category_id) {
            $productCategoryIDs = [];// get category ids of the provided product IDs from product table;
            if (!in_array($voucher->category_id, $productCategoryIDs)) {
                return response()->json(['message' => 'Voucher is not applicable to the selected products'], 400);
            }
        }

        $discount = $this->calculateDiscount($voucher, $request->input('totalAmount'));
        $voucher->increment('used_count');
        $voucher->decrement('remaining_count');

        return response()->json(['discount' => $discount, 'newTotal' => $request->input('totalAmount') - $discount]);
    }

    private function calculateDiscount($voucher, $totalAmount)
    {
        if ($voucher->discount_type == 'percentage') {
            return ($totalAmount * $voucher->discount_value) / 100;
        }

        return $voucher->discount_value;
    }
}





