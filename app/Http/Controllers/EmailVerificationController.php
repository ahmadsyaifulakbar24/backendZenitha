<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormater;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EmailVerificationController extends Controller
{
    public function email_verification($user_id)
    {
        $user_id = Crypt::decryptString($user_id);
        $user = User::find($user_id);

        $error = null;
        if($user->status == 'active') {
            $error = 'Your account is active';
        } else {
            $user->update([
                'email_verified_at' => now(),
                'status' => 'active',
            ]);
        }

        return view('mail.verificationMail', compact('user', 'error'));
    }
}
