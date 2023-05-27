<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderStatusWhenOrderSuccess
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderSuccessEvent $event)
    {
        $order = $event->order;
        $order->status = 'processing';
        $order->save();
    }
}
