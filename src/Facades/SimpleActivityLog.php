<?php

namespace Mrcookie\SimpleActivityLog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mrcookie\SimpleActivityLog\SimpleActivityLog
 */
class SimpleActivityLog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mrcookie\SimpleActivityLog\SimpleActivityLog::class;
    }
}
