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
        $data =  QAccount::getCallsThisWeek()->toView();
        return view('calls.test')
            ->with('weekly_calls', $data['calls'])
            ->with('sum', $data['sum']);
    }

    public function hardRefresh()
    {

        $success = QAccount::reload();

        if (!$success) {
            Session::flash('error', "Can't pull data from QA-World server. Please try again");
            return response("Can't load data from server", 400);
        }
        Session::flash('success', "Successfully loaded data from QA-World.");
        return response('The data has been successfully loaded', 200);
    }
}