<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUserController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'phone_number' => ['required', 'integer'],
            'role' => ['required', 'in:super admin,admin,distributor,reseller,member,customer'],
            'status' => ['required', 'in:active,not_active']
        ]);

        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'status' => $request->status
        ]);

        return ResponseFormatter::success(
            new UserResource($user),
            'success update user data'
        );
    }
}
