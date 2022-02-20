<?php

namespace App\Http\Controllers\API\WebSetting;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebSetting\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'banner' => ['required', 'image', 'mimes:png,jpg,jpeg,gif'],
            'order' => ['required', 'integer', 'unique:banners,order'],
            'url' => ['nullable', 'url']
        ]);

        $input = $request->all();
        $path = FileHelpers::upload_file('banner', $request->banner);
        $input['banner'] = $path;

        $banner = Banner::create($input);
        return ResponseFormatter::success(new BannerResource($banner), 'success get banner data');
    }

    public function get()
    {
        $banner = Banner::orderBy('order', 'asc')->get();
        return ResponseFormatter::success(BannerResource::collection($banner), 'success get banner data');
    }

    public function show(Banner $banner)
    {
        return ResponseFormatter::success(new BannerResource($banner), 'success get banner data');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif'],
            'order' => ['required', 'integer', 'unique:banners,order,'.$banner->id],
            'url' => ['nullable', 'url']
        ]);

        $input = $request->all();
        if($request->banner) {
            $path = FileHelpers::upload_file('banner', $request->banner);
            $input['banner'] = $path;
            Storage::disk('public')->delete($banner->banner);
        }

        $banner->update($input);
        return ResponseFormatter::success(new BannerResource($banner), 'success update banner data');
    }

    public function delete(Banner $banner)
    {
        Storage::disk('public')->delete($banner->banner);
        $banner->delete();
        return ResponseFormatter::success(null, 'success delete banner data');
    }
}
