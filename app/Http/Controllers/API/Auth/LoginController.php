<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);
    
            if(!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active'])) {
                return ResponseFormatter::error([
                    'message' => 'unauthorization',
                ], 'authentication failed', 500);
            }
    
            $user = User::with('roles')->where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password)) {
                throw new \Exception('invalid credentials');
            }
    
            $tokenResult = $user->createToken('HMIc8dMmRUYfUB3HovOlAEqDzPpb0fyj')->accessToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => new UserResource($user)
            ], 'Authenticated');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'someting went wrong',
                'error' => $e->getMessage()
            ], 'Authentication failed', 500);
        }
    }
}
