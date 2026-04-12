<?php

use Atomcoder\Toasty\ToastManager;

if (! function_exists('toasty')) {
    function toasty(): ToastManager
    {
        return app(ToastManager::class);
    }
}
