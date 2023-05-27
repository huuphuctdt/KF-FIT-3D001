<?php

namespace App\Http\Controllers;

use App\Events\OrderSuccessEvent;
use App\Http\Service\SmsService;
use App\Mail\OrderUserEmail;
use App\Models\Order;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VnpayController extends Controller
{
    private $smsService;
    public function __construct(SmsService $smsService){
        $this->smsService = $smsService;
    }
    
    public function callbackVnpay(Request $request){
        $orderId = $request->get('vnp_TxnRef');
        $order = Order::find($orderId);

        session()->put('cart', []);

        if($request->get('vnp_ResponseCode') === '00'){
            event(new OrderSuccessEvent($order));
            
            return redirect()->route('index')->with('message', 'Dat hang thanh cong');
        }else{
            //update status order = 'cancel'
            $order->status = 'cancel';
            $order->save();
            //update status order_payment_methods = 'cancel'
            $paymentMethods = $order->order_payment_methods;
            foreach($paymentMethods as $paymentMethod){
                //App\Models\OrderPaymentMethod
                $paymentMethod->status = 'cancel';
                $paymentMethod->save();
            }
            return redirect()->route('index')->with('message', 'Dat hang that bai');
        }
    }
}
