<?php

namespace App\Providers;

use App\Call;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $total_earnings = Call::select('amount_earned')
                ->where('submitted_at', '>=', Carbon::parse('last monday'))
                ->get()
                ->sum('amount_earned');
            view()->share('total_earnings', $total_earnings);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}