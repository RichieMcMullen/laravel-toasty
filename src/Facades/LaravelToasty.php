<?php

namespace Atomcoder\Toasty\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelToasty extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Atomcoder\Toasty\ToastyFactory::class;
    }
}
