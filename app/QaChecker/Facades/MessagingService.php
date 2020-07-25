<?php

namespace App\QaChecker\Facades;

use Illuminate\Support\Facades\Facade;

class MessagingService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'messaging';
    }
}