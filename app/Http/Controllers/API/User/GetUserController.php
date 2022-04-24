<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GetUserController extends Controller
{
    public function fetch(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:active,not_active'],
            'search' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer'],
        ]);
        $limit = $request->input('limit', 10);

        $user = User::query();
        if($request->status) {
            $user->where('status', $request->status);
        }

        if($request->search) {
            $user->where('name', 'like', '%'.$request->search.'%');
        }

        return ResponseFormatter::success(
            UserResource::collection($user->paginate($limit))->response()->getData(true),
            'success get user data'
        );
    }

    public function get_customer(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:active,not_active'],
            'search' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer'],
            'parent' => ['nullable', 'in:yes,no'],
            'parent_id' => [
                'nullable', 
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('type', 'staff');
                })
            ]
        ]);
        $limit = $request->input('limit', 10);
        $user = User::role([
            'distributor', 
            'reseller',
            'member',
            'customer'
        ]);

        if($request->status) {
            $user->where('status', $request->status);
        }

        if($request->search) {
            $user->where('name', 'like', '%'.$request->search.'%');
        }

        if($request->parent)
        {
            ($request->parent == 'yes') ? $user->whereNotNull('parent_id') : $user->whereNull('parent_id');
        }

        if($request->parent_id) 
        {
            $user->where('parent_id', $request->parent_id);
        }

        return ResponseFormatter::success(UserResource::collection($user->paginate($limit))->response()->getData(true), 'success get user data');
    }

    public function get_staff(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:active,not_active'],
            'search' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer']
        ]);
        $limit = $request->input('limit', 10);

        $user = User::find($request->user()->id);
        $cek_role = in_array('super admin', $user->getRoleNames()->toArray());
        if($cek_role) {
            $roles = ['super admin', 'admin', 'finance'];
        } else {
            $roles = ['admin', 'finance'];
        }

        $user = User::role($roles)->where('id', '!=', $user->id);
        if($request->status) {
            $user->where('status', $request->status);
        }

        if($request->search) {
            $user->where('name', 'like', '%'.$request->search.'%');
        }
        return ResponseFormatter::success(UserResource::collection($user->paginate($limit))->response()->getData(true), 'success get user data');
    }

    public function show (User $user)
    {
        return ResponseFormatter::success(
            new UserResource($user),
            'success get user data'
        );
    }
}
