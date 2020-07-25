<?php

namespace App\Providers;

use App\QaChecker\QAccount;
use Illuminate\Support\ServiceProvider;

class QAServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('qaccount', function () {
            return new QAccount();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}