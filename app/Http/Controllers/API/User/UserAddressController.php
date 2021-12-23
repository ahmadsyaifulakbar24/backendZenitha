<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserAddressResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserAddressController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required', 
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('status', 'active');
                })
            ], 
            'label' => ['required', 'string'],
            'recipients_name' => ['required', 'string'],
            'province_id' => [ 'required', 'exists:provinces,id' ],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(function($query) use ($request) {
                    return $query->where('province_id', $request->province_id);
                })
            ],
            'house_number' => ['required', 'string'],
            'phone_number' => ['nullable', 'integer'],
            'address_description' => ['required', 'string'],
        ]);

        $input = $request->all();

        $user = User::find($request->user_id);
        $user_address = $user->user_address()->create($input); 

        return ResponseFormatter::success(
            new UserAddressResource($user_address),
            $this->message('create')
        );
    }
    
    public function fetch()
    {

    }

    public function show(User $user)
    {

    } 

    public function update(Request $request, User $user) {

    }

    public function delete(User $user)
    {

    }

    public function message ($type)
    {
        return 'success '.$type.' user data';
    }
}
