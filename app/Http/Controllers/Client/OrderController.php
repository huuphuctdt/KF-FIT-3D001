<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Service\SmsService;
use App\Http\Service\VnpayService;
use App\Mail\OrderUserEmail;
use App\Mail\TestMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class OrderController extends Controller
{
    private $vnpayService;
    public function __construct(VnpayService $vnpayService){
        $this->vnpayService = $vnpayService;
    }

    public function checkout()
    {
        $cart = session()->get('cart') ?? [];

        return view('clients.pages.checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        //validate request from user
        $cart = session()->get('cart') ?? [];

        // try{
        //     DB::beginTransaction();
        //     //insert table A
        //     //insert table B
        //     //insert table C
        //     DB::commit();
        // }catch(\Exception $e){
        //     DB::rollBack();
        // }

        $arrayData = DB::transaction(function () use($request, $cart) {
            //Create records into table Order
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'address' => $request->get('address'),
                'status' => 'pending',
                'payment_method' => $request->get('payment_method'),
            ]);

            //Create records into table OrderItems
            $totalBalance = 0;
            foreach($cart as $productId => $item) {
                $orderItems = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'name' => $item['name'],
                ]);
                $totalBalance += $item['qty'] * $item['price'];
            }

            //Create records into table OrderPaymentMethod
            $orderPaymentMethod = OrderPaymentMethod::create([
                'order_id' =>  $order->id,
                'payment_provider' => $request->get('payment_method'),
                'total_balance' => $totalBalance,
                'status' => 'pending',
            ]);

            $user = User::find($order->user_id);
            $user->phone_number =  $request->get('phone_number');
            $user->save();

            return compact('order', 'totalBalance', 'orderPaymentMethod');
        });   

        $paymentMethod = $request->get('payment_method');
        if(in_array($paymentMethod, ['vnpay_atm', 'vnpay_creditcart'])){
            $vnp_Url = $this->vnpayService->urlVnPay($arrayData['order'], $arrayData['totalBalance'], $paymentMethod);
            return Redirect::to($vnp_Url);
        }
        
        session()->put('cart', []);
            
        return redirect()->route('index')->with('message', 'Dat hang thanh cong');
    }

    public function getDetailOrderByCode($id){
        $shortURL = \AshAllenDesign\ShortURL\Models\ShortURL::findByKey($id);

        $destinationUrl = $shortURL->destination_url;
        $params = parse_url($destinationUrl)['path'] ?? [];
        $id = explode('/', $params)[3] ?? null;

        $order = Order::findOrFail($id);
    
        return view('clients.pages.user_order', ['order' => $order]);
    }
}
