<?php

namespace App\Http\Controllers\API\ProductSlider;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSlider\ProductSliderResource;
use App\Models\ProductSlider;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class ProductSliderController extends Controller
{
    public function get()
    {
        $product_slider = ProductSlider::all();
        return ResponseFormatter::success(ProductSliderResource::collection($product_slider), 'success get product slider data');
    }

    public function create(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);
        $product_slider = ProductSlider::firstOrCreate([ 'product_id' => $request->product_id ]);
        return ResponseFormatter::success(new ProductSliderResource($product_slider), 'success create product slider data');
    }

    public function delete(ProductSlider $product_slider)
    {
        $product_slider->delete();
        return ResponseFormatter::success(null, 'success delete product slider data');
    }
}
