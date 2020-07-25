<?php

namespace App\Http\Controllers;

use App\Call;
use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dates = $this->getDaysInBetween(Carbon::parse('last Monday'), now());
        $all_calls = $dates->mapWithKeys(function ($date) {
            $formatted_date = $this->formatDisplayDate($date);
            $calls_in_day = Call::with('company')
                ->where('submitted_at', $date)
                ->orderByDesc('created_at')
                ->get();
            $summary = $this->getSummaryOfCalls($calls_in_day);
            return [$formatted_date => ['calls' => $calls_in_day, 'summary' => $summary]];
        });

        $companies = Company::all();

        return view('calls.index')
            ->with('calls', $all_calls)
            ->with('companies', $companies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'rating' => 'required|min:1|max:5',
            'duration' => 'required|array|min:2',
            'duration.0' => 'required|min:1|max:30',
            'duration.1' => 'required|min:0|max:59',
            'amount_earned' => 'required'
        ], [], [
            'company_id' => 'Company name',
        ]);

        $duration_parsed = ($request->duration[0] * 60) + $request->duration[1];

        Call::create([
            'company_id' => $request->company_id,
            'rating' => $request->rating,
            'duration' => $duration_parsed,
            'amount_earned' => $request->amount_earned,
            'submitted_at' => Carbon::now()
        ]);

        Session::flash('success', 'Call Added Successfully!');
        return redirect(route('calls.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Call $call)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call)
    {
        //
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
            $dates->add($date->format('Y-m-d'));
        }

        return $dates;
    }

    private function getSummaryOfCalls($calls)
    {
        $rating = $calls->average(function ($call) {
            return $call->rating;
        });

        $total_duration = $calls->sum(function ($call) {
            return $call->duration;
        });

        $total_amount_earned = $calls->sum(function ($call) {
            return $call->amount_earned;
        });

        $summary = [
            'rating' => round($rating, 2),
            'total_duration' => formatTimestamp($total_duration),
            'total_amount_earned' => $total_amount_earned,
            'total_amount_earned_in_peso' => convertToPeso($total_amount_earned)
        ];
        return $summary;
    }
}