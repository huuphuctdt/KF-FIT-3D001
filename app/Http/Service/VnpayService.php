<?php

namespace App\Http\Service;

use App\Mail\OrderUserEmail;
use App\Models\Order;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class VnpayService
{
    public function urlVnPay($order, $totalBalance, $paymentMethod): string {
        $vnp_TxnRef = $order->id; //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $totalBalance; // Số tiền thanh toán
        $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = ($paymentMethod === 'vnpay_creditcart' ? 'INTCARD' : 'VNBANK'); //Mã phương thức thanh toán
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => config('vnpay.vnp_tmncode'),
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => config('vnpay.vnp_returnurl'),
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = config('vnpay.vnp_url') . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, config('vnpay.vnp_hashsecret'));
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }

}
