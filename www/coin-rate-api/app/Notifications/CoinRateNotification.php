<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CoinRateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $rate;

    public function __construct($rate)
    {
        $this->rate = $rate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('BTC/UAH Rate Update.')
                    ->line('Current BTC/UAH rate is 1 BTC = ' . $this->rate . " UAH")
                    ->line('Thank you for subscribing for our services!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
