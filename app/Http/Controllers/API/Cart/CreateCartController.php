<?php

namespace App\Http\Controllers\API\Cart;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Models\ProductCombination;
use App\Models\User;
use Illuminate\Http\Request;

class CreateCartController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'product_slug' => ['required', 'exists:product_combinations,product_slug'],
            'quantity' => ['required', 'integer']
        ]);

        $user = User::find($request->user()->id);
        $carts =  $user->carts()->where('product_slug', $request->product_slug);
        if($carts->count() < 1) {
            $cart = $user->carts()->create([
                'product_slug' => $request->product_slug,
                'quantity' => $request->quantity
            ]);
        } else {
            $product_combination = ProductCombination::where('product_slug', $request->product_slug)->first();
            $cart = $carts->first();
            $new_quantity = $cart->quantity + $request->quantity;
            if($new_quantity > $product_combination->stock) {
                return ResponseFormatter::error([
                    'message' => 'cannot add this product',
                    'stock' => $product_combination->stock,
                    'quantity' => $cart->quantity,
                ], 'add product failed', 422);
            } else {
                $cart->update([
                    'quantity' => $new_quantity
                ]);
            }
        }
        return ResponseFormatter::success(new CartResource($cart), 'success create cart data');
    }
}
