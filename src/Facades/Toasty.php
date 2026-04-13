<?php

namespace Atomcoder\Toasty\Facades;

use Illuminate\Support\Facades\Facade;

class Toasty extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Atomcoder\Toasty\ToastManager::class;
    }
}
