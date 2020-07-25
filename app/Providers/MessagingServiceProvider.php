<?php

namespace App\Providers;

use App\QaChecker\MessagingService;
use Illuminate\Support\ServiceProvider;

class MessagingServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('messaging', function () {
            return new MessagingService();
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