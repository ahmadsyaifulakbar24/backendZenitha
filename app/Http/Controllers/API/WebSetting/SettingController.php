<?php

namespace App\Http\Controllers\API\WebSetting;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebSetting\SettingResource;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function get()
    {
        $web_setting = WebSetting::first();
        return ResponseFormatter::success(new SettingResource($web_setting), 'success get web setting data');
    }

    public function setting(Request $request)
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'integer'],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(function($query) use ($request) {
                    return $query->where('province_id', $request->province_id);
                })
            ],
            'district_id' => [
                'required',
                Rule::exists('districts', 'id')->where(function($query) use ($request) {
                    return $query->where('city_id', $request->city_id);
                })
            ],
            'postal_code' => ['nullable', 'integer'],
            'address' => ['nullable', 'string'],

            'fb_status' => ['required', 'boolean'],
            'fb' => ['nullable', 'url'],
            'tw_status' => ['required', 'boolean'],
            'tw' => ['nullable', 'url'],
            'yt_status' => ['required', 'boolean'],
            'yt' => ['nullable', 'url'],
            'ig_status' => ['required', 'boolean'],
            'ig' => ['nullable', 'url'],
        ]);
        $input = $request->all();
        
        if($request->logo) {
            $path = FileHelpers::upload_file('setting', $request->logo);
        }

        $count = WebSetting::count();
        if($count < 1) {
            if($request->logo) {
                $input['logo'] = $path;
            }
            $web_setting = WebSetting::create($input);
        } else {
            if($request->logo) {
                $input['logo'] = $path;
            }

            $web_setting = WebSetting::first();
            $web_setting->update($input);
        }

        return ResponseFormatter::success(new SettingResource($web_setting), 'success create setting data');;
    }
}
