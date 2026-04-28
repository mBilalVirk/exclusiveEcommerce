<?php
namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Define which channel the event broadcasts on.
     */
    public function broadcastOn(): array
    {
        // Private channel means only the specific user can listen
        return [
            new PrivateChannel('orders.' . $this->order->user_id),
        ];
    }

    /**
     * Data to send to the frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'status' => $this->order->status,
            'message' => "Order #{$this->order->id} is now {$this->order->status}!",
        ];
    }
}