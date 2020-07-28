<?php

namespace App\QaChecker;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessagingService
{
    public function sendMessage($number = '09481403263', $message)
    {
        $api_code = env('ITEXTMO_API_CODE');
        $api_password = env('ITEXTMO_API_PASSWORD');

        Log::info("A message was sent to $number and message of $message");
        $response = $this->itexmo($number, $message, $api_code, $api_password);
        Log::info("A message was sent with a response of $response");
    }

    function itexmo($number, $message, $apicode, $passwd)
    {
        $ch = curl_init();
        $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
        curl_setopt($ch, CURLOPT_URL, env('ITEXTMO_SEND_MESSAGE_ENDPOINT'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query($itexmo)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
        curl_close($ch);
    }
}