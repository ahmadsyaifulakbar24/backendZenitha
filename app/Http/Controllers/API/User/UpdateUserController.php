<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateUserController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $role =  ($user->type = 'staff') ? ['super admin', 'admin', 'finance'] : ['distributor', 'reseller', 'member', 'customer'];

        $this->validate($request, [
            'name' => ['required', 'string'],
            'phone_number' => ['required', 'integer'],
            'role' => [
                'required', 
                Rule::in($role)
            ],
            'status' => ['required', 'in:active,not_active']
        ]);

        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'status' => $request->status
        ]);
        $user->syncRoles($request->role);

        return ResponseFormatter::success(
            new UserResource($user),
            'success update user data'
        );
    }
}
