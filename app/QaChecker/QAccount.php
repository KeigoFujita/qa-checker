<?php

namespace App\QaChecker;

use App\QaChecker\Facades\MessagingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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

    protected $QA_WORLD_ACCOUNT_INFO_URL = 'https://app.qa-world.com/contractor_users/';

    protected $dom = null;
    protected $row_headers = ['owner', 'rated', 'completion_date', 'audio_minutes', 'amount_earned', 'quality_rating', 'call_id', 'passing_transcript_example', 'quality_rating_date', 'transcript_flags', 'quality_rating_reason', 'quality_rating_notes'];


    private $selected_calls = null;
    private $from = null;
    private $to = null;

    private $recently_rated_calls = null;
    private $sendNotifications = false;

    public function __construct()
    {
        $this->dom = new Dom;
        $this->selected_calls = collect([]);
        $this->from = Carbon::parse('this week Monday', 'GMT+8');
        $this->to = Carbon::parse('this week Sunday', 'GMT+8');

        $this->recently_rated_calls = collect([]);
    }

    public function getCallsThisWeek($owner = null)
    {
        $this->selected_calls = $this->getCallsInWeek($owner);
        return $this;
    }

    public function getCallsFromLastWeek($owner = null)
    {
        $this->selected_calls = $this->getCallsInWeek(1, $owner);
        return $this;
    }

    public function getCallsFromWeekAgo($week, $owner = null)
    {
        $this->selected_calls = $this->getCallsInWeek($week, $owner);
        return $this;
    }

    public function getCallsInWeek($week = 0, $owner = null)
    {

        if ($week > 0) {
            $this->from = $this->from->subWeeks($week);
            $this->to = $this->to->subWeeks($week);
        }

        $from = $this->from;
        $to = $this->to;

        $calls = collect([]);
        $all_calls = collect($this->getAllCalls());
        $calls = $all_calls->filter(function ($call) use ($from, $to) {
            $completion_date = Carbon::parse($call->completion_date, 'GMT+8');
            // dump($completion_date->format('M d, Y') . " = ", $completion_date->gte($from) && $completion_date->lte($to));
            return $completion_date->gte($from) && $completion_date->lte($to);
        });
        $calls = $owner ? $calls->where('owner', $owner) : $calls;
        return $calls;
    }

    public function toRaw()
    {
        return $this->selected_calls;
    }

    public function getCallsBetween($start, $end)
    {
    }


    public function getAllCalls()
    {
        $calls_exists = Storage::disk('public')->exists('calls.json');
        if (!$calls_exists) {
            $calls = Storage::disk('public')->put('calls.json', json_encode([]));
        };

        $calls = Storage::disk('public')->get('calls.json');
        return json_decode($calls);
    }

    /**
     *  reload data from QA World
     *
     * @return 
     */

    public function reload()
    {
        $accounts = $this->fetchAccounts();
        $calls_updated = collect([]);
        $accounts->each(function ($account) use ($calls_updated) {
            $account_calls = $this->fetchAccountCalls($account);
            $calls_updated->push($account_calls);
        });
        $calls_updated = $calls_updated->flatten(1);

        if ($this->sendNotifications) $this->checkAllRatedCalls($calls_updated);

        $calls_updated_json = $calls_updated->toJson();

        Storage::disk('public')->put('calls.json', $calls_updated_json);

        Log::info('calls.json has been updated');

        return !empty($this->getAllCalls());
    }

    public function reloadWithNotifations()
    {
        $this->sendNotifications = true;
        return $this->reload();
    }

    private function checkAllRatedCalls($updated_calls)
    {
        $old_calls = collect($this->getAllCalls());
        $old_calls->each(function ($call) use ($updated_calls) {
            $call_id = $call->call_id;
            $updated_call = $updated_calls->where('call_id', $call_id)->first();
            if (
                !empty($updated_call)
                && $call->call_id == $updated_call['call_id']
                && $updated_call['rated'] == true
                && $call->rated == false
            ) {
                $this->recently_rated_calls->push((object)$updated_call);
            }
        });

        $this->dispatchNotifications();
    }

    private function dispatchNotifications()
    {
        $this->recently_rated_calls->each(function ($call) {
            $this->notify($call);
        });
    }

    private function notify($call)
    {
        $call_id = $call->call_id;
        $rating = $call->quality_rating;
        $amount_earned = convertToPeso($call->amount_earned);

        $number = env('ITEXTMO_TO_NUMBER');
        $message = "";

        switch ($rating) {
            case 1:
            case 2:
                $message = "Alert (Failed Rating)! Transcription ($call_id) got a failed rating of $rating. Please check immediately";
                break;
            default:
                $message = "Congrats! Transcription ($call_id) is now rated with a score of $rating. Amount earned : Php. $amount_earned";
                break;
        }
        MessagingService::sendMessage($number, $message);
    }

    public function fetchAccounts()
    {
        $exists = Storage::disk('root')->exists('accounts.json');
        $accounts_json = $exists ? Storage::disk('root')->get('accounts.json') : null;
        return $accounts_json ?  collect(json_decode($accounts_json)) : collect([]);
    }

    private function fetchAccountCalls($account)
    {
        $response = $this->makeRequest($account);
        $account_information = $this->parseResponse($response, $account->account_name)->flatten(1);
        return $account_information;
    }

    private function makeRequest($account)
    {
        try {
            $this->headers['Cookie'] = $account->account_cookie;
            $url = $this->QA_WORLD_ACCOUNT_INFO_URL . $account->account_id;
            $response_text = Http::withHeaders($this->headers)->get($url)->body();
            return $response_text;
        } catch (Exception $e) {
            Log::error($e);
            Log::error("Can't make request to the QA Website");
            return "";
        }
    }

    private function parseResponse($response, $owner)
    {
        $parser = $this->dom;
        $parser->loadStr($response);
        $tables = $parser->find('table');

        $account_calls = collect([]);
        $tables->each(function ($table) use ($account_calls, $owner) {
            $table_information = $this->getTableData($table, $owner);
            $account_calls->push($table_information);
        });
        return $account_calls;
    }

    private function getTableData($table, $owner = "")
    {
        $rows = $table->find('tr');
        $table_information = collect([]);
        $index = -1;

        foreach ($rows as $row) {
            $index++;
            if ($index == 0) continue;
            $row_information = $this->getRowData($row, $owner);
            $table_information->push($row_information);
        }

        return $table_information->toArray();
    }

    private function getRowData($row, $owner = "")
    {

        $row_headers = collect($this->row_headers);
        $isRated = true;

        $row_information = collect([$owner, $isRated]);
        $tds = $row->find('td');
        $index = 1;


        foreach ($tds as $td) {

            $td_text = "";

            switch ($index) {
                case 3:
                    $isRated = $td->text != "NOT YET RATED";
                    $td_text = $isRated  ?  floatval(ltrim($td->text, '$')) : 0;
                    break;
                case 4:
                    $td_text = $isRated ?  floatval($td->text) : 0;
                    break;
                case 5:
                    $td_text = $td->find('a')[0]->text;
                    break;
                default:
                    $td_text = $td->text;
                    break;
            }

            $row_information->push($td_text);
            $index++;
        }

        $row_information[1] = $isRated;

        return $this->map_headers_to_values($row_headers, $row_information);
    }

    private function map_headers_to_values($row_headers, $row_information)
    {
        $row = collect([]);
        for ($i = 0; $i < $row_headers->count(); $i++) {
            $row_header = $row_headers->get($i);
            $row_data = $row_information->get($i);
            $row->put($row_header, $row_data);
        }
        return $row;
    }


    public function toView()
    {

        $calls = $this->selected_calls;
        $to = $this->to->eq(Carbon::parse('this week Sunday', 'GMT+8')) ? now('GMT+8') : $this->to;

        $dates = getDaysInBetween($this->from, $to);

        $parsed_calls = $dates->map(function ($date) use ($calls) {
            $calls_in_day = $calls->where('completion_date', $date)->sortByDesc('quality_rating');
            $sum = $calls_in_day->sum('amount_earned');
            $num_calls = $calls_in_day->where('quality_rating', '>=', 3)->count();
            $failed_rating = $calls_in_day->whereIn('quality_rating', [1, 2])->count();
            $unrated_calls = $calls_in_day->where('quality_rating', 'N/A')->count();
            $average_rating = $calls_in_day->average('quality_rating');

            return [
                'date' => formatDisplayDate($date),
                'calls' => $calls_in_day,
                'sum' => $sum,
                'num_calls' => $num_calls,
                'failed_rating' => $failed_rating,
                'unrated_calls' => $unrated_calls,
                'average_rating' => $average_rating
            ];
        });

        $sum = $calls->sum('amount_earned');

        return [
            'calls' => $parsed_calls,
            'sum' => $sum
        ];
    }
}