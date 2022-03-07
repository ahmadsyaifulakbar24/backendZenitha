<?php

namespace App\Http\Controllers\API\WebSetting\OtherSetting;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebSetting\FooterBannerResource;
use App\Models\OtherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FooterBannerController extends Controller
{
    public function get()
    {
        $footer_banner = OtherSetting::where('category', 'footer_banner')->first();
        return ResponseFormatter::success(new FooterBannerResource($footer_banner), 'success get footer banner data');
    }
    
    public function footer_banner(Request $request)
    {
        $query = OtherSetting::where('category', 'footer_banner');
        $request->validate([
            'banner' => ['required', 'image', 'mimes:png,jpg,jpeg,gif']
        ]);

        $path = FileHelpers::upload_file('setting', $request->banner);
        if($query->count() < 1) {
            $footer_banner = OtherSetting::create([
                'category' => 'footer_banner',
                'content' => $path,
                'type' => 'image'
            ]);
        } else {
            if($request->banner) {
                $footer_banner = $query->first();
                Storage::disk('public')->delete($footer_banner->content);
                $footer_banner->update([ 'content' => $path ]);
            }
        }

        return ResponseFormatter::success(new FooterBannerResource($footer_banner), 'success create footer banner data');
    }

}
