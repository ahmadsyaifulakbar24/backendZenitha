<?php

namespace App\Http\Controllers\API\Region;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function province($province_id = null)
    {
        $message = "success get province data";
        if($province_id) {
            $province = Province::find($province_id);
        } else {
            $province = Province::all();
        }
        return ResponseFormatter::success(
            $province,
            $message
        );
    }

    public function city(Request $request, $city_id = null)
    {
        $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
        ]);

        if($city_id) {
            $city = City::find($city_id);
        } else {
            $city = City::where('province_id', $request->province_id)->get();
        }

        return ResponseFormatter::success(
            $city,
            'succes get city data'
        );
    }

    public function district(Request $request, $district_id = null)
    {
        $request->validate([
            'city_id' => ['required', 'exists:cities,id'],
        ]);

        if($district_id) {
            $district = District::find($district_id);
        } else {
            $district = District::where('city_id', $request->city_id)->get();
        }

        return ResponseFormatter::success(
            $district,
            'success get district data'
        );
    }
}
