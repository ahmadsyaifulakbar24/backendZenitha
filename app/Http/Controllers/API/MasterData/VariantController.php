<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\VariantResource;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function create(Request $request) 
    {
        $request->validate([
            'variant_name' => ['required', 'string', 'unique:variants,variant_name'],
            'image' => ['required', 'boolean']
        ]);

        $input = $request->all();
        $variant = Variant::create($input);
        return ResponseFormatter::success(new VariantResource($variant), 'success create variant data');
    }

    public function fetch()
    {
        $variant = Variant::all();
        return ResponseFormatter::success(
            VariantResource::collection($variant),
            'success get variant data'
        );
    }

    public function show(Variant $variant)
    {
        return ResponseFormatter::success(
            new VariantResource($variant),
            'success get variant data'
        );
    }

    public function update(Request $request, Variant $variant)
    {
        $request->validate([
            'variant_name' => ['required', 'string', 'unique:variants,variant_name,'.$variant->id],
            'image' => ['required', 'boolean']
        ]);

        $input = $request->all();
        $variant->update($input);
        return ResponseFormatter::success(new VariantResource($variant), 'success update variant data');
    }

    public function delete(Variant $variant)
    {
        $pvo_count = $variant->product_variant_option()->count();
        if($pvo_count > 0) {
            return ResponseFormatter::error([
                'message' => 'cannot detele this variant because it already has child data',
            ], 'delete variant data failed', 422);
        }

        $variant->delete();
        return ResponseFormatter::success(null, 'success delete varinat data');
    }
}
