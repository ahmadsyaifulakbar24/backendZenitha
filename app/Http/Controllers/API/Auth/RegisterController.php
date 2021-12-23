<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Mail\SendActivationEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone_number' => ['required', 'integer'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', Password::min(8)],
            'role' => ['required', 'in:super admin,admin,distributor,reseller,member,customer'],
        ]);
        $role = $request->role;

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'status' => 'not_active'    
            ]);
            $user->assignRole($role);

            Mail::to($request->email)->send(new SendActivationEmail($user->name, $user->id));
            return ResponseFormatter::success(
                new UserResource($user),
                'check email to verify your account'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ], 'register failed', 500);
        }
    }
}
