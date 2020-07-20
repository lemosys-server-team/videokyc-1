<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class OTPVerification extends Notification
{
    use Queueable;
    protected $otp;
    protected $mobile_number;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otp,$mobile_number)
    {
        $this->token = $otp;
        $this->mobile_number = $mobile_number;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Welcome to ').config('app.name', 'Laravel'))
            ->line(Lang::get('Thank you for creating your '.config('app.name', 'Laravel').' account. Please Login with your below Credential through the Application.'))
            ->line(Lang::get('Your Mobile Number is :mobile_number & OTP is :otp', ['mobile_number' => $this->mobile_number, 'otp' => $this->token]));
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
