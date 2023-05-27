<?php

namespace App\Listeners;

use App\Events\OrderSuccessEvent;
use App\Http\Service\SmsService;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsToCustomerWhenOrderSuccess
{
    private $smsService;
    public function __construct(SmsService $smsService){
        $this->smsService = $smsService;
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
        $shortURLObject = ShortURL::destinationUrl(route('user.order.code', ['id' => $order->id]))->make();
        $urlKey = $shortURLObject->url_key;
        $urlSms  = route('user.order.code', ['id' => $urlKey]);

        $this->smsService->sms($order->phone_number, 'Ban dat hang thanh cong : '. $urlSms);
    }
}
