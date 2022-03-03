<?php

namespace App\Http\Controllers\API\Discount;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Discount\ShippingDiscountResource;
use App\Models\ShippingSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShippingDiscountController extends Controller
{
    public function show()
    {
        $shipping_discount = ShippingSetting::first();
        return ResponseFormatter::success(new ShippingDiscountResource($shipping_discount), 'success get shipping discount data');
    }

    public function shipping_discount(Request $request)
    {
        $request->validate([
            'minimum_price' => ['required', 'integer'],
            'max_shipping_discount' => ['required', 'integer'],
            'start_date' => ['required','date_format:Y-m-d H:i:s', 'after_or_equal:'.Carbon::now()],
            'end_date' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:start_date']
        ]);
        $input = $request->all();
        $count = ShippingSetting::count();
        if($count < 1) {
            $shipping_discount = ShippingSetting::create($input);
        } else {
            $shipping_discount = ShippingSetting::first();
            $shipping_discount->update($input);
        }
        return ResponseFormatter::success(new ShippingDiscountResource($shipping_discount));
    }
}
