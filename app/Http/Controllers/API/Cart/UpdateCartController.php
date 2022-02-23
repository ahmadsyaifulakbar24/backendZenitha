<?php

namespace App\Http\Controllers\API\Cart;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class UpdateCartController extends Controller
{
    public function update_quantity(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => ['required', 'integer']
        ]);

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return ResponseFormatter::success(new CartResource($cart), 'success update quantity data');
    }
}
