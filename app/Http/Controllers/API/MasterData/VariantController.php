<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\VariantResource;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
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
}
