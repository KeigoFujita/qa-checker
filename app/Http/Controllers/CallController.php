<?php

namespace App\Http\Controllers;

use App\QaChecker\Facades\QAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CallController extends Controller
{
    public function index(Request $request)
    {
        $accounts = QAccount::fetchAccounts();
        $week_ago = $request->week_ago ? $request->week_ago : 0;
        $account_id = $request->account_id ? $request->account_id : 0;
        $account = $accounts->where('account_id', $account_id)->first();
        $account_name =  $account ? $account->account_name : null;
        $data =  QAccount::getCallsFromWeekAgo($week_ago, $account_name)->toView();
        return view('calls.index')
            ->with('weekly_calls', (object)$data['calls'])
            ->with('sum', $data['sum'])
            ->with('week_ago', $week_ago)
            ->with('account_id', $account_id)
            ->with('accounts', $accounts);
    }

    public function hardRefresh()
    {

        $success = QAccount::reloadWithNotifations();
        if (!$success) {
            Session::flash('error', "Can't pull data from QA-World server. Please try again");
            return response("Can't load data from server", 400);
        }
        Session::flash('success', "Successfully loaded data from QA-World.");
        return response('The data has been successfully loaded', 200);
    }
}