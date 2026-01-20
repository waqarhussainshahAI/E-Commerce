<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $order;

    public $message;

    public function __construct($order, $message)
    {
        $this->order = $order;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database', 'mail'];
    }

    public function broadcastOn(): array
    {
        Log::info(" broadcastOn App.Models.User.{$this->order->user_id}");

        return [
            new PrivateChannel("App.Models.User.{$this->order->user_id}"),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {

        Log::info('Broadcast notification prepared', [
            'order_id' => $this->order->id ?? 'no-order',
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
        ]);

        return new BroadcastMessage([
            'orderId' => $this->order->id,
            'message' => "your Order #{$this->order->id} status is  $this->message",
        ]);
    }

    // /**
    //  * Get the mail representation of the notification.
    //  */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @return array<string, mixed>
    //  */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "your Order #{$this->order->id} status is  $this->message",
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order #'.$this->order->id.' Delivery Status')
            ->greeting('Hello '.$notifiable->name)
            ->line('Your Order #'.$this->order->id.' Status is '.$this->message)
            ->action('Visit Dashboard', url(env('CLIENT_URL').'/cart'))
            ->line('Thank you for using our application!');
    }
}
