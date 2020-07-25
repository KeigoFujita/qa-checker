<?php

namespace App\Http\Controllers;

use App\QaChecker\Facades\MessagingService;
use App\QaChecker\Facades\QAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PHPHtmlParser\Dom;

class TestController extends Controller
{

    public function index()
    {

        // MessagingService::sendMessage('09481403263', 'Matulog na kayo!!');
        // die();

        if (!Storage::disk('public')->exists('calls.json')) {
            return redirect(url('hard'));
        }

        $json = Storage::disk('public')->get('calls.json');
        $calls = json_decode($json);

        $calls = collect($calls);
        $dates = $this->getDaysInBetween(Carbon::parse('last Monday'), now());

        $parsed_calls = $dates->map(function ($date) use ($calls) {
            $calls_in_day = $calls->where('completion_date', $date)->sortByDesc('quality_rating');
            $sum = $calls_in_day->sum('amount_earned');
            $num_calls = $calls_in_day->where('quality_rating', '>=', 3)->count();
            $failed_rating = $calls_in_day->whereIn('quality_rating', [1, 2])->count();
            $unrated_calls = $calls_in_day->where('quality_rating', 'N/A')->count();

            return [
                'date' => $this->formatDisplayDate($date),
                'calls' => $calls_in_day,
                'sum' => $sum,
                'num_calls' => $num_calls,
                'failed_rating' => $failed_rating,
                'unrated_calls' => $unrated_calls,
            ];
        });

        $sum = $calls->sum('amount_earned');

        return view('calls.test')
            ->with('weekly_calls', collect($parsed_calls))
            ->with('sum', $sum);
    }

    public function hardRefresh()
    {
        $calls = QAccount::request();
        $json_calls = collect($calls)->toJson();

        Storage::disk('public')->put('calls.json', $json_calls);

        if (empty($calls)) {
            Session::flash('error', "Can't pull data from QA-World server. Please try again");
            return response("Can't load data from server", 400);
        }
        Session::flash('success', "Successfully loaded data from QA-World.");
        return response('Success', 200);
    }

    private function formatDisplayDate($date)
    {
        $parsed_date = Carbon::parse($date);

        return $parsed_date->isToday() ?  $parsed_date->format('F d, Y') . " (Today)" :  $parsed_date->format('F d, Y (l)');
    }

    private function getDaysInBetween($from = NULL, $to = NULL)
    {
        $from = isset($from) ? $from  : Carbon::parse('last Monday');
        $to = isset($to) ? $to  : now();

        $dates = collect([]);

        for ($date = $to; $date->gt($from->copy()); $date->subDay()) {
            $dates->add($date->format('M d, Y'));
        }

        return $dates;
    }
}