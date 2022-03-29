<?php

namespace App\Http\Controllers\API\Courier;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Courier\CourierResource;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function get(Request $request)
    {
        $request->validate([
            'active' => ['nullable', 'in:yes,no'],
        ]);

        $courier = Courier::query();
        if($request->active) {
            $active = ($request->active == 'yes') ? 1 : 0;
            $courier->where('active', $active);
        }
        return ResponseFormatter::success(CourierResource::collection($courier->get()), 'success get courier data');
    }

    public function update_active(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'exists:couriers,slug'],
            'active' => ['required', 'in:0,1']
        ]);

        $courier = Courier::where('slug', $request->slug)->first();
        $courier->update([ 'active' => $request->active ]);
        return ResponseFormatter::success(new CourierResource($courier), 'success update courier data');
    }
}
 