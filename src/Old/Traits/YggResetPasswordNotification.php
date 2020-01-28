<?php


namespace Ygg\Old\Traits;

use Ygg\Old\Notifications\ResetPassword as ResetPasswordNotification;

trait YggResetPasswordNotification
{


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
