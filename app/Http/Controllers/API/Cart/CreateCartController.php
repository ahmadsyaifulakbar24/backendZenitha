<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CreateCartController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer']
        ]);

        $user = User::find($request->user()->id);

        return $input[$request->product_id] = [
            'quantity' => $request->quantity
        ];
        $user->carts()->sync($input);
    }
}
