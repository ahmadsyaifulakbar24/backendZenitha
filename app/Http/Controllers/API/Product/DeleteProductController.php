<?php

namespace App\Http\Controllers\API\product;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DeleteProductController extends Controller
{
    public function delete(Product $product)
    {
        $product->delete();
        return ResponseFormatter::success(null, 'success delete product data');
    }
}
