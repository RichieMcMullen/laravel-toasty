<?php

use Atomcoder\Toasty\ToastManager;

if (! function_exists('laravel_toasty')) {
    function laravel_toasty(): ToastManager
    {
        return app(ToastManager::class);
    }
}
