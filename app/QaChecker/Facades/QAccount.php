<?php

namespace App\QaChecker\Facades;

use Illuminate\Support\Facades\Facade;

class QAccount extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'qaccount';
    }
}