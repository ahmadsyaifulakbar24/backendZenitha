<?php

namespace App\Http\Controllers\API\MasterData;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\MasterData\VariantOptionResource;
use App\Models\VariantOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\FuncCall;

class VariantOptionController extends Controller
{
    public function create(Request $request) {
        $request->validate([
            'variant_id' => ['required', 'exists:variants,id'],
            'variant_option_name' => [
                'required',
                Rule::unique('variant_options', 'variant_option_name')->where(function($query) use ($request) {
                    return $query->where('variant_id', $request->variant_id);
                })
            ]
        ]);

        $variant_option = VariantOption::create($request->all());
        return ResponseFormatter::success(
            new VariantOptionResource($variant_option),
            $this->message('create')
        );
    }

    public function fetch(Request $request)
    {
        $request->validate([
            'variant_id' => ['nullable', 'exists:variants,id']
        ]);

        $variant_option = VariantOption::query();
        if ($request->variant_id) {
            $variant_option->where('variant_id', $request->variant_id);
        }
        
        return ResponseFormatter::success(
            $variant_option->get(),
            $this->message('get')
        );
    }

    public function show(VariantOption $variant_option)
    {
        return ResponseFormatter::success(
            new VariantOptionResource($variant_option),
            $this->message('get')
        );
    }

    public function update(Request $request, VariantOption $variant_option)
    {
        if($variant_option->default == 1) {
            return ResponseFormatter::errorValidation([
                'variant_id' => 'variant options id is invalid'
            ], 'update variant option failed');
        }

        $request->validate([
            'variant_option_name' => [
                'required',
                Rule::unique('variant_options', 'variant_option_name')->ignore($variant_option->id)->where(function($query) use ($request) {
                    return $query->where('variant_id', $request->variant_id);
                })
            ]
        ]);

        $variant_option->update($request->all());

        return ResponseFormatter::success(
            new VariantOptionResource($variant_option),
            $this->message('update')
        );
    }

    public function delete(VariantOption $variant_option)
    {
        $variant_option->delete();
        return ResponseFormatter::success(null, 'success delete variant options');
    }
    public function message($type)
    {
        return 'success '.$type.' variant option data';
    }
}
