<?php

namespace App\Http\Controllers\API\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\WebConfig;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    public function reset_password_mail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $user = User::where('email', $request->email)->first();
        $url = WebConfig::where('name', 'reset_password_url')->pluck('value')->first();
        Mail::to($request->email) ->send(new ResetPassword($url, $user->name, $token));

        try {
            return ResponseFormatter::success( null, 'success send reset password mail' );
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ], 'reset password failed', 500);
        }
    }

    public function reset_password_token(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', Password::min(8)]
        ]);

        $token = Crypt::decryptString($request->token);
        $cek_token = DB::table('password_resets')->where('token', $token)->first();
        if(!$cek_token) {
            return ResponseFormatter::error([
                'message' => 'invalid token !'
            ], 'reset password failed', 422);
        }

        $user = User::where('email', $cek_token->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        DB::table('password_resets')->where('email', $cek_token->email)->delete();
        return ResponseFormatter::success(new UserResource($user), 'success reset password data');
    }

    public function without_confirmation(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', Password::min(8)],
        ]);

        $user = User::find($request->user_id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        return ResponseFormatter::success(new UserResource($user), 'success reset password data');
    }

    public function with_old_password (Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', Password::min(8)],
        ]);

        $user = User::find(Auth::user()->id);
        if(!Hash::check($request->old_password, $user->password)) {
            return ResponseFormatter::error([
                'message' => 'old password is invalid'
            ], 'reset password failed', 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);
        return ResponseFormatter::success(new UserResource($user), 'success reset password data');
    }
}
