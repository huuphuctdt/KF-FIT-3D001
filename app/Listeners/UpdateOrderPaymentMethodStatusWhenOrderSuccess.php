<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderPaymentMethodStatusWhenOrderSuccess
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
        $paymentMethods = $order->order_payment_methods;
        foreach($paymentMethods as $paymentMethod){
            //App\Models\OrderPaymentMethod
            $paymentMethod->status = 'success';
            $paymentMethod->save();
        }
    }
}
