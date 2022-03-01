<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\FileHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\SizePackResource;
use App\Models\SizePack;
use Illuminate\Http\Request;

class SizePackController extends Controller
{
    public function get()
    {
        $size_pack = SizePack::all();
        return ResponseFormatter::success(SizePackResource::collection($size_pack), 'success get size pack data');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'file' => ['required', 'image', 'mimes:png,jpg,jpeg,gif']
        ]);
        $path = FileHelpers::upload_file('size_pack', $request->file);
        $size_pack = SizePack::create([
            'name' => $request->name,
            'file' => $path
        ]);
     
        return ResponseFormatter::success(new SizePackResource($size_pack), 'success create size pack data');
    }

    public function show(SizePack $size_pack)
    {
        return ResponseFormatter::success( new SizePackResource($size_pack), 'success get size pack data');
    }

    public function update(Request $request, SizePack $size_pack)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'file' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif']
        ]);

        $input['name'] = $request->name;
        if($request->file) {
            $path = FileHelpers::upload_file('size_pack', $request->file);
            $input['file'] = $path;
        }

        $size_pack->update($input);
        
        return ResponseFormatter::success(new SizePackResource($size_pack), 'success update size pack data');
    }

    public function delete(SizePack $size_pack)
    {
        $size_pack->delete();
        return ResponseFormatter::success(null, 'success delete size pack data');
    }
}
