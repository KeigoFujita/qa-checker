<?php

namespace App\QaChecker;

use Illuminate\Support\Facades\Http;

class MessagingService
{
    public function sendMessage($number, $message)
    {
        $api_send_endpoint = env('ITEXTMO_SEND_MESSAGE_ENDPOINT');
        $api_code = env('ITEXTMO_API_CODE');
        $api_password = env('ITEXTMO_API_PASSWORD');

        dump($this->itexmo($number, $message, $api_code, $api_password));
        // $request_data = [
        //     '1' => $number,
        //     '2' => $message,
        //     '3' => $api_code,
        //     'passwd' => $api_password
        // ];

        // dump($request_data);

        // $response = Http::post($api_send_endpoint, $request_data);
        // $response_text = $response->body();


        // $status = Http::get(
        //     'https://www.itexmo.com/php_api/api.php',
        //     ['apicode' => $api_code]
        // );
        // dump($status);
        // dump($status->body());
        // dump($response);
        // dump($response_text);
    }

    function itexmo($number, $message, $apicode, $passwd)
    {
        $ch = curl_init();
        $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
        curl_setopt($ch, CURLOPT_URL, "https://www.itexmo.com/php_api/api.php");
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