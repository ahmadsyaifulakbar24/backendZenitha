<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateUserController extends Controller
{
    public function customer(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone_number' => ['required', 'integer'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', Password::min(8)],
            'role' => ['required', 'in:distributor,reseller,member,customer'],
        ]);
        $role = $request->role;
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'status' => 'active', 
                'type' => 'customer',
            ]);
            $user->assignRole($role);
            return ResponseFormatter::success(
                new UserResource($user),
                'success create user customer data'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ], 'register failed', 500);
        }
    }

    public function staff(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone_number' => ['required', 'integer'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', Password::min(8)],
            'role' => ['required', 'in:super admin,admin,finance'],
        ]);
        $role = $request->role;
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'status' => 'active', 
                'type' => 'staff',
            ]);
            $user->assignRole($role);
            return ResponseFormatter::success(
                new UserResource($user),
                'success create user staff data'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ], 'register failed', 500);
        }
    }
}
