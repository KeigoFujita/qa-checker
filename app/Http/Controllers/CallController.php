<?php

namespace App\Http\Controllers;

use App\QaChecker\Facades\QAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CallController extends Controller
{
    public function index()
    {

        if (!Storage::disk('public')->exists('calls.json')) {
            $this->fetchDataFromAPI();
        }

        $json = Storage::disk('public')->get('calls.json');
        $calls = json_decode($json);

        $calls = collect($calls);
        $dates = getDaysInBetween(Carbon::parse('last Monday'), now());

        $parsed_calls = $dates->map(function ($date) use ($calls) {
            $calls_in_day = $calls->where('completion_date', $date)->sortByDesc('quality_rating');
            $sum = $calls_in_day->sum('amount_earned');
            $num_calls = $calls_in_day->where('quality_rating', '>=', 3)->count();
            $failed_rating = $calls_in_day->whereIn('quality_rating', [1, 2])->count();
            $unrated_calls = $calls_in_day->where('quality_rating', 'N/A')->count();

            return [
                'date' => formatDisplayDate($date),
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

        $calls = $this->fetchDataFromAPI();
        if (empty($calls)) {
            Session::flash('error', "Can't pull data from QA-World server. Please try again");
            return response("Can't load data from server", 400);
        }
        Session::flash('success', "Successfully loaded data from QA-World.");
        return response('The data has been successfully loaded', 200);
    }


    private function fetchDataFromAPI()
    {
        $calls = QAccount::fetchData();
        $json_calls = collect($calls)->toJson();

        Storage::disk('public')->put('calls.json', $json_calls);
        Log::notice('Updated the calls');
        return $calls;
    }
}