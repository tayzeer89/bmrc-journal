<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;


class ReviewerResetPasswordNotification extends ResetPassword
{
    /*
    |--------------------------------------------------------------------------
    | Reviewer Reset URL
    |--------------------------------------------------------------------------
    */

    protected function resetUrl($notifiable): string
    {
        return route(
            'reviewer.password.reset',
            [
                'token' => $this->token,

                'email' =>
                    $notifiable->getEmailForPasswordReset(),
            ]
        );
    }
}