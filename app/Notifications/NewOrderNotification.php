<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' or 'broadcast' if needed
    }

   public function toDatabase($notifiable)
{
    return [
        'order_id' => $this->order->id,
        'message' => 'New order placed for your shop: ' . ($this->order->product->shop->name ?? 'Unknown Shop'),
        'shop_id' => $this->order->product->shop->id ?? null,
        'customer_name' => $this->order->user->name ?? 'Unknown Customer',
    ];
}

}
