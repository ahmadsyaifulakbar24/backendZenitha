<?php

namespace App\Http\Controllers\API\Discount;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Discount\DiscountResource;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\FuncCall;

class DiscountController extends Controller
{
    public function get(Request $request) {
        $request->validate([
            'type' => [
                Rule::requiredIf(empty($request->id)),
                'in:group,user'
            ],
            'user_id' => [
                Rule::requiredIf($request->type == 'user'), 
                'exists:users,id'
            ],
            'group_id' => [
                Rule::requiredIf($request->type == 'group'),
                'nullable', 
                'exists:roles,id'
            ],
        ]);

        $discount = Discount::query();
        if($request->type == 'user') {
            $discount->where('user_id', $request->user_id);
        } else {
            $discount->where('group_user_id', $request->group_user_id);
        }

        return ResponseFormatter::success(DiscountResource::collection($discount->get()), 'success get discount data');
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:group,user'],
            'user_id' => [
                Rule::requiredIf($request->type == 'user'),
                'exists:users,id'
            ],
            'group_user_id' => [
                Rule::requiredIf($request->type == 'group'),
                'exists:roles,id'
            ],
            'discount' => ['required', 'integer'],
            'category_id' => ['required', 'exists:categories,id'],
            'start_date' => ['required','date_format:Y-m-d H:i:s', 'after_or_equal:'.Carbon::now()],
            'end_date' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:start_date']
        ]);

        $input = $request->except([
            'user_id',
            'group_user_id'
        ]);

        if($request->type == 'group') {
            $input['group_user_id'] = $request->group_user_id;
        } else {
            $input['user_id'] = $request->user_id;
        }

        $discount = Discount::create($input);

        return ResponseFormatter::success(new DiscountResource($discount), 'success create discount data');
    }

    public function show(Discount $discount)
    {
        return ResponseFormatter::success(new DiscountResource($discount), 'success get discount data');
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'discount' => ['required', 'integer'],
            'start_date' => ['required','date_format:Y-m-d H:i:s', 'after_or_equal:'.Carbon::now()],
            'end_date' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:start_date']
        ]);

        $discount->update([
            'discount' => $request->discount,
            'start_date' => $request->start_date,
            'end_data' => $request->end_data,
        ]);

        return ResponseFormatter::success(new DiscountResource($discount), 'success update discount data');
    }
}
