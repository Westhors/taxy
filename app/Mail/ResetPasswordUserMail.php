<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email;
    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function build(): ResetPasswordUserMail
    {
        return $this->markdown('mail.ResetPasswordUserMail')
            ->subject('Your New Password')
            ->with([
                'email' => $this->email,
                'password' => $this->password
            ]);
    }
}
