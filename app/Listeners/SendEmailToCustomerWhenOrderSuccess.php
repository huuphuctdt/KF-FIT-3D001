<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use App\Mail\OrderUserEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToCustomerWhenOrderSuccess
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
        
        $emailCustomer = $order->user->email;
        $emailCustomer = 'nguyenlyhuuphuc@gmail.com';

        Mail::to($emailCustomer)->send(new OrderUserEmail($order));
    }
}
