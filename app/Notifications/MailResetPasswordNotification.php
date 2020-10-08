<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Crypt;

class MailResetPasswordNotification extends ResetPassword
{
    use Queueable;

    public $token;
    protected $pageUrl;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->pageUrl = config('frontend.email_reset_url'.$token);
        // we can set whatever we want here, or use .env to set environmental variables
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $hash = Crypt::encrypt($notifiable->getKey());
        $link = config('frontend.email_reset_url').$hash;
        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->line("Hello! You are receiving this email because we received a password reset request for your account.")
            ->action('Reset Password', $link)
            ->line("This password reset link will expire in ".config('auth.passwords.users.expire')." minutes")
            ->line("If you did not request a password reset, no further action is required.");
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
