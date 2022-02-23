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
            // 'product_id' => ['required', 'exists:products,id'],
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
            $cart = $carts->first();
            $cart->update([
                'quantity' => $cart->quantity + $request->quantity
            ]);
        }
        return ResponseFormatter::success(new CartResource($cart), 'success create cart data');
    }
}
