<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class AuthorResetPasswordNotification extends ResetPassword
{
    /*
    |--------------------------------------------------------------------------
    | Author Password Reset URL
    |--------------------------------------------------------------------------
    */

    protected function resetUrl($notifiable): string
    {
        return route(
            'author.password.reset',
            [
                'token' => $this->token,

                'email' =>
                    $notifiable
                        ->getEmailForPasswordReset(),
            ]
        );
    }
}