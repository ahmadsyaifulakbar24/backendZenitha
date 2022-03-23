<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $name, $token)
    {
        $this->url = $url;
        $this->name = $name;
        $this->token = Crypt::encryptString($token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_NAME'))
        ->subject('Reset Password')
        ->view('mail.resetPassword')
        ->with([
            'url' => $this->url,
            'name' => $this->name,
            'token' => $this->token,
        ]);
    }
}
