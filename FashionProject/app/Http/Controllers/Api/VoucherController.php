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
            'discountType' => 'required|string',
            'discountValue' => 'required|numeric',
            'expiryDate' => 'required|date',
            'minPurchaseAmount' => 'required|numeric',
            'pointRequired' => 'required|integer',
            'type' => 'required|string',
            'maxUsage' => 'integer|nullable',
            'applicableProducts' => 'string|nullable',
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
            'discountType' => 'string',
            'discountValue' => 'numeric',
            'expiryDate' => 'date',
            'minPurchaseAmount' => 'numeric',
            'pointRequired' => 'integer',
            'type' => 'string',
            'maxUsage' => 'integer|nullable',
            'applicableProducts' => 'string|nullable',
            'distribution_channels' => 'string|nullable',
            'created_count' => 'integer|nullable',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());
        $voucher->remaining_count = $voucher->created_count - $voucher->usedCount;
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

        if ($request->input('totalAmount') < $voucher->minPurchaseAmount) {
            return response()->json(['message' => 'Total amount is less than the minimum purchase amount required for this voucher'], 400);
        }

        if ($voucher->maxUsage && $voucher->usedCount >= $voucher->maxUsage) {
            return response()->json(['message' => 'Voucher has been used the maximum number of times'], 400);
        }

        if ($voucher->applicableProducts) {
            $applicableProducts = explode(',', $voucher->applicableProducts);
            $intersect = array_intersect($request->input('productIDs'), $applicableProducts);
            if (empty($intersect)) {
                return response()->json(['message' => 'Voucher is not applicable to the selected products'], 400);
            }
        }

        $discount = $this->calculateDiscount($voucher, $request->input('totalAmount'));
        $voucher->increment('usedCount');
        $voucher->decrement('remaining_count');

        return response()->json(['discount' => $discount, 'newTotal' => $request->input('totalAmount') - $discount]);
    }

    private function calculateDiscount($voucher, $totalAmount)
    {
        if ($voucher->discountType == 'percentage') {
            return ($totalAmount * $voucher->discountValue) / 100;
        }

        return $voucher->discountValue;
    }
}


