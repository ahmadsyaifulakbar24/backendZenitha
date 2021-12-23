<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    public function __invoke(User $user)
    {
        $result = $user->delete();
        return ResponseFormatter::success(
            $result,
            'success delete user'
        );
    }
}
