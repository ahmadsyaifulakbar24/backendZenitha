<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class GetUserController extends Controller
{
    public function fetch(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:active,not_active'],
            'limit' => ['nullable', 'integer'],
        ]);
        $limit = $request->input('limit', 10);

        $user = User::query();
        if($request->status) {
            $user->where('status', $request->status);
        }

        return ResponseFormatter::success(
            UserResource::collection($user->paginate($limit)),
            'success get user data'
        );
    }

    public function get_customer(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:active,not_active'],
            'limit' => ['nullable', 'integer']
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

        return ResponseFormatter::success(UserResource::collection($user->paginate($limit)), 'success get user data');
    }

    public function get_staff(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:active,not_active'],
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
        return ResponseFormatter::success(UserResource::collection($user->paginate($limit)), 'success get user data');
    }

    public function show (User $user)
    {
        return ResponseFormatter::success(
            new UserResource($user),
            'success get user data'
        );
    }
}
