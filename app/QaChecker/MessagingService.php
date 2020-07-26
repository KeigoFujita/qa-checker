<?php

namespace App\QaChecker;

use Illuminate\Support\Facades\Http;

class MessagingService
{
    public function sendMessage($number, $message)
    {
        $api_code = env('ITEXTMO_API_CODE');
        $api_password = env('ITEXTMO_API_PASSWORD');
        $this->itexmo($number, $message, $api_code, $api_password);
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