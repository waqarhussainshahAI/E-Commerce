<?php

namespace App\Notifications;

use App\Mail\OrderConfirmationMail;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $order)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast', 'database'];
    }

    public function broadcastOn(): array
    {

        return [
            new PrivateChannel("App.Models.User.{$this->order->user_id}"),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {

        return new BroadcastMessage([
            'orderId' => $this->order->id,
            'message' => "your Order #{$this->order->id} is placed successfully",
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return new OrderConfirmationMail($this->order, $notifiable);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "your Order #{$this->order->id} is placed successfully",

        ];
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
