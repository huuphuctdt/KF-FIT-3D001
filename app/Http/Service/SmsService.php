<?php

namespace App\Http\Service;

use Twilio\Rest\Client;

class SmsService
{
    public function sms($receiverNumber, $message){
        try {
            $receiverNumber = '+84352405575';
            $account_sid = config("twilio.twilio_account_sid");
            $auth_token = config("twilio.twilio_auth_token");
            $twilio_number = config("twilio.twilio_phone_number");
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
  
        } catch (\Exception $e) {
            // dd("Error: ". $e->getMessage());
        }
    }
}
