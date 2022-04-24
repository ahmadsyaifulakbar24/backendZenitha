<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParentUserController extends Controller
{
    public function parent (Request $request, $user_id = null)
    {
        $request->validate([
            'user_id' => [
                'required', 
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('type', 'customer');
                })
            ],
            'parent_id' => [
                'required', 
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('type', 'staff');
                })
            ]
        ]);

        if($user_id) {
            $old_user = User::find($user_id);
            $old_user->update([ 'parent_id' => null ]);
        }
        
        $user = User::find($request->user_id);
        $user->update([ 'parent_id' => $request->parent_id ]);

        return ResponseFormatter::success(new UserResource($user), 'success set parent user data');
    }

    public function delete(Request $request) 
    {
        $request->validate([
            'user_id' => [
                'required', 
                Rule::exists('users', 'id')->where(function($query) {
                    return $query->where('type', 'customer');
                })
            ],
        ]);

        $user = User::find($request->user_id);
        $user->update(['parent_id', null]);
        return ResponseFormatter::success(new UserResource($user), 'susccess delete user parent data');
    }
}
