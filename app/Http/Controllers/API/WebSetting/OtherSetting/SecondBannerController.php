<?php

namespace App\Http\Controllers\API\WebSetting\OtherSetting;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebSetting\BannerResource;
use App\Http\Resources\WebSetting\SecondBannerResource;
use App\Models\OtherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SecondBannerController extends Controller
{
    public function get()
    {
        $second_banner = OtherSetting::where('category', 'second_banner')->orderBy('order', 'asc')->get();
        return ResponseFormatter::success(SecondBannerResource::collection($second_banner), 'success get second banner data');
    }

    public function create(Request $request)
    {
        $request->validate([
            'banner' => ['required', 'image', 'mimes:png,jpg,jpeg,gif'],
            'order' => ['required', 'integer']
        ]);
        
        $path = FileHelpers::upload_file('setting', $request->banner);
        $second_banner = OtherSetting::create([
            'category' => 'second_banner',
            'content' => $path,
            'type' => 'image',
            'order' => $request->order,
        ]);

        return ResponseFormatter::success(new SecondBannerResource($second_banner), 'success create second banner data');
    }

    public function show(OtherSetting $second_banner)
    {
        return ResponseFormatter::success(new SecondBannerResource($second_banner), 'success get second banner data');
    }

    public function update(Request $request, OtherSetting $second_banner)
    {
        $request->validate([
            'banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif'],
            'order' => ['required', 'integer']
        ]);

        if($request->banner) {
            $path = FileHelpers::upload_file('setting', $request->banner);
            $input['content'] = $path;
            Storage::disk('public')->delete($second_banner->content);
        }

        $input['order'] = $request->order;
        $second_banner->update($input);

        return ResponseFormatter::success(new SecondBannerResource($second_banner), 'success update second banner data');
    }
}
