<?php

namespace App\Http\Controllers\API\Cart;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class DeleteCartController extends Controller
{
    public function __invoke(Cart $cart)
    {
        $cart->delete();
        return ResponseFormatter::success(null, 'success delete card dat');
    }
}
