<?php

use Atomcoder\Toasty\PendingToasty;
use Atomcoder\Toasty\ToastyFactory;

if (! function_exists('laravel_toasty')) {
    function laravel_toasty(mixed $target = null): PendingToasty
    {
        return app(ToastyFactory::class)->for($target);
    }
}
