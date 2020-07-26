<?php

namespace App\QaChecker;

use App\QaChecker\Facades\MessagingService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPHtmlParser\Dom;

class QAccount
{
    protected $headers = [
        'Host' => 'app.qa-world.com',
        'Connection' => 'keep-alive',
        'Cache-Control' => 'max-age=0',
        'Upgrade-Insecure-Requests' => '1',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-User' => '?1',
        'Sec-Fetch-Dest' => 'document',
        'Referer' => 'https://app.qa-world.com/calls',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Cookie' => '',
        'If-None-Match' => 'W/"f7465fccbac0d13c88dcdbc4fbdd2018"',
    ];

    protected $HOPE_COOKIE = "_platform_session=RUpvWHNDQnpCd0VEblNJaHBYQlpLMXJzRllMM0k4c2c3bkkvVXYxNW1rNXRmY2FScXhsM0xubGNWcTRFTEZOOE9yQzlrWnE3YWRHZm1FQlF6Mm0zdmdaU2IzTnd6SmEwRnFCbUg4RXRMRGpIWlpYRjdEVHpuaHB2VU9XZDJGRHFrMng1KzhUQkRlbmx2eDRvR2Y2N20zdG5vUnBRTVJXTEFMUEJqR1k4RWF0azNscDBnUmlENGRYZ0NrSktSVFdoU1ZPcHRKc0xSWHN3dU9nb1JWYU5SMWVtQ2hyeFFqSllOWklrVjNpc0UyMmdYdnRpaElmdjdOVUdNaTFMR0VXcDBkL21WSy8xVTRsYVZPUWpRcEUyVWhYTElGUlM5OHFMaUhveXFVRkk2T1lBSldHMzV4UXU4dXFxUnVuM05VdTYtLVhaUWErclB4dVdaZXFjZkFSbWtlY3c9PQ%3D%3D--b8ad8cef64eed5c6aa63daabefb90292e702c23b; _fbp=fb.0.1594814110871.329054244; mp_fc93b52301c5e7ec351390ced47e3982_mixpanel=%7B%22distinct_id%22%3A%20%22172b13016dd31c-0555eb3cd559f-f7d123e-144000-172b13016de241%22%2C%22%24device_id%22%3A%20%22172b13016dd31c-0555eb3cd559f-f7d123e-144000-172b13016de241%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fapp.qa-world.com%2F%22%2C%22%24initial_referring_domain%22%3A%20%22app.qa-world.com%22%7D";
    protected $KEIGO_COOKIE = "fs_uid=rs.fullstory.com#96X73#6304226288877568:4649491844448256/1618060342; _fbp=fb.1.1595023631244.703286775; mp_fc93b52301c5e7ec351390ced47e3982_mixpanel=%7B%22distinct_id%22%3A%20%221714e74dd3025-0885d1c04098f2-f313f6d-144000-1714e74dd311bb%22%2C%22%24device_id%22%3A%20%221714e74dd3025-0885d1c04098f2-f313f6d-144000-1714e74dd311bb%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fapp.qa-world.com%2Fcontractor_users%2Fsign_in%22%2C%22%24initial_referring_domain%22%3A%20%22app.qa-world.com%22%7D; _platform_session=SGgzR2JUWjBPUWJrNy82NlZqaEVZbHFpTko2SE8waXQ3NEErWmJnbFJDK2hUU0VpM0lvaTVScXlHL0hSdWpjWFYyNW1PNWNnWDFOSi91Q0FWdXA5eHRKbXNTaVErdE8yUDZXaEtvOU9oZVBFREdBVHpYVkVHVndhWUU5czVjczdWTklwNnNweEQ1Y1ltL2d6Q3dUeFFhSXFqQUpWU3pGUDk5cFVCN3lSMzNteGF2bXpFT2wvTzBmellqbVVsdFIvRldwSWtGYVlXdXBOZC9WQnpnMUJpLytIdFhWcnFhdzlXN043RU80NUZpNlRIVDZ0WVlZY0R2QU9pMmoyaWtZeC0tc3ZHbm9tYW4zaW10NGJKS1hXMUFWZz09--73cdf1e35591831c90ad56a7d9645d26c4173660";
    protected $MARISOL_COOKIE = "_platform_session=ZmE1UXkxMFZMdFl1QnlWSU40QjFrWjZhbkVPM3RMMnRQemwzS0czZUFHR0hEUCtGeFlqblY3elg5MkdNMXNhd05iS1dmeU0wUk1ydEhsc0p1YzMzOXR3QVdYK3FVMXhNci9TaUpJV25DTXM5UWFUWEhNVWZlallaY1E2Q2x0QnQ4RG03ZnExa2w3SEduSDNxTVBwUlJ1T0F3MTQrSTUrT1VFV0tpazlzanN2RVZtanNuWG5CNWRsLzRhYTlWZEdWdjZuOXdCMllwNnhLZmsvWXFwSG90VzZhV1JYWFpQOGZaeUx6eUhob3JydVBreldYSmxyZHNsaFFGcUxuandDY205YWl4SHZaMElMOUR5Umd1OVZrUTVPVmdKTWRHdHdzTXM4ZjhVMHVlb0xiYVJabWFOMWhCeEUyK01KMitYZ3MtLWtYRWttaFJUeWpWRTd6QVF0U010QUE9PQ%3D%3D--7a18e6b1977ff80e421ae52d5fd1013299c68b27; _fbp=fb.0.1595589927539.1480946981; mp_fc93b52301c5e7ec351390ced47e3982_mixpanel=%7B%22distinct_id%22%3A%20%221736726911e40b-0d704033bc2d49-4353760-144000-1736726911f7cf%22%2C%22%24device_id%22%3A%20%221736726911e40b-0d704033bc2d49-4353760-144000-1736726911f7cf%22%2C%22%24initial_referrer%22%3A%20%22https%3A%2F%2Fapp.qa-world.com%2Fcontractor_users%2Fsign_in%22%2C%22%24initial_referring_domain%22%3A%20%22app.qa-world.com%22%7D";

    protected $HOPE_ID = 230308;
    protected $KEIGO_ID = 125314;
    protected $MARISOL_ID = 176608;

    protected $APP_URL = 'https://app.qa-world.com/contractor_users/';

    public function fetchData()
    {
        $hope_response = $this->makeRequest($this->HOPE_COOKIE, $this->HOPE_ID, 'Hope');
        $keigo_response = $this->makeRequest($this->KEIGO_COOKIE, $this->KEIGO_ID, 'Keigo');
        $marisol_response = $this->makeRequest($this->MARISOL_COOKIE, $this->MARISOL_ID, 'Marisol');

        $response = array_merge($hope_response, $keigo_response, $marisol_response);
        $this->notifyUsers($response);

        return $response;
    }


    private function makeRequest($cookie, $id, $owner)
    {
        try {
            $this->headers['Cookie'] = $cookie;
            $url = $this->APP_URL . $id;
            $response = Http::withHeaders($this->headers)->get($url);
            $response_text = $response->body();
            return $this->parseResponse($response_text, $owner);
        } catch (Exception $e) {
            Log::error($e);
            return [];
        }
    }


    private function parseResponse($response, $owner)
    {
        $dom = new Dom();
        $dom->loadStr($response);
        $table = $dom->find('table')[0];
        $rows = $table->find('tr');

        $table_data = collect([]);

        $rows->each(function ($row) use ($table_data, $owner) {

            $row_information = [];

            $data_per_row = $row->find('td');

            $count = count($data_per_row);
            $isRated = true;

            for ($i = 0; $i < $count; $i++) {

                $row_information['owner'] = $owner;
                $data = $data_per_row[$i];


                switch ($i) {
                    case 0:
                        $row_information['completion_date'] = $data->text;
                        break;

                    case 1:
                        $row_information['audio_minutes'] = $data->text;
                        break;
                    case 2:
                        if ($data->text == "NOT YET RATED") {
                            $row_information['amount_earned'] = 0;
                            $isRated = false;
                        } else {
                            $amount_earned = floatval(ltrim($data->text, '$'));
                            $row_information['amount_earned'] = $amount_earned;
                        }
                        break;
                    case 3:

                        if (!$isRated) {
                            $row_information['quality_rating'] = "N/A";
                        } else {
                            $row_information['quality_rating'] = $data->text;
                        }
                        break;
                    case 4:
                        $row_information['call_id'] = $data->find('a')[0]->text;
                        break;
                    case 6:
                        $row_information['quality_rating_date'] = $data->text;
                        break;
                }
            }

            $table_data->push($row_information);
        });

        $table_data->forget(0);

        return $table_data->toArray();
    }


    public function notifyUsers($updated_calls)
    {
        if (!Storage::disk('public')->exists('calls.json')) {
            return;
        }

        $old_calls = json_decode(Storage::disk('public')->get('calls.json'));
        $has_message_sent = false;

        collect($old_calls)->each(function ($old_call) use ($updated_calls, $has_message_sent) {

            $old_id = $old_call->call_id;
            $new_call = collect($updated_calls)->where('call_id', $old_id)->first();

            if ($old_call->quality_rating == "N/A" && $new_call['quality_rating'] != "N/A") {

                $number = "09481403263";
                $message = "";

                switch ($new_call['quality_rating']) {
                    case '5':
                    case '4':
                    case '3':
                        $message = "Transcription (" . $old_id . ") is now rated with a score of " . $new_call['quality_rating'] . ". " .
                            "Total amount earned: Php." . convertToPeso($new_call['amount_earned']) . ".";
                        break;

                    default:
                        $message = "Transcription (" . $old_id . ") got a failed rating of " . $new_call['quality_rating'] . ". " .
                            "Account: " . $new_call['owner'] . ". Please check immediately.";
                        break;
                }
                Log::info('A message is send to' . $new_call['owner'] . "   | Message: $message");
                MessagingService::sendMessage($number, $message);
                $has_message_sent = true;
                return;
            }
        });

        if (!$has_message_sent) {
            Log::info('No message sent');
        }
    }
}