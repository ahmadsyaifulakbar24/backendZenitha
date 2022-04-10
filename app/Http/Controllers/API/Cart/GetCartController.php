<?php

namespace App\Http\Controllers\API\Cart;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;

class GetCartController extends Controller
{
    public function get(Request $request)
    {
        $user = User::find($request->user()->id);
        $cart = Cart::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return ResponseFormatter::success(CartResource::collection($cart), 'success get cart data');
    }
}
