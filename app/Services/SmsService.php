<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

//use Illuminate\Config;

class SmsService extends MailService
{

    public function sendSms($phone_number, $message)
    {
        $SMS_SENDER = Config::get('sms.sms_sender_id');
        $SMS_USERNAME = Config::get('sms.sms_username');
        $SMS_PASSWORD = Config::get('sms.sms_password');
        $SMS_BASE_URL = Config::get('sms.sms_base_url');

        $client = new Client();

        $response = $client->post($SMS_BASE_URL, [
            'verify'    =>  false,
            'form_params' => [
                'username' => $SMS_USERNAME,
                'password' => $SMS_PASSWORD,
                'message' => $message,
                'sender' => $SMS_SENDER,
                'mobiles' => $phone_number,
            ],
        ]);


        return $response = json_decode($response->getBody(), true);
    }
}
