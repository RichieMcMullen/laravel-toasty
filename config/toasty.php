<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toast Event Names
    |--------------------------------------------------------------------------
    |
    | These browser events power the toast stack. "event_name" is what the
    | global window.toast() helper and Livewire trait dispatch. The layout
    | event allows switching between the default stacked layout and the fully
    | expanded layout at runtime.
    |
    */
    'event_name' => 'toasty-show',
    'layout_event_name' => 'toasty-set-layout',

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | Toasts flashed from PHP are stored in the session until the next request
    | where the rendered toast stack consumes and shows them on page load.
    |
    */
    'session_key' => 'toasty.toasts',

    /*
    |--------------------------------------------------------------------------
    | Default Appearance
    |--------------------------------------------------------------------------
    */
    'position' => 'top-center',
    'layout' => 'default',
    'duration' => 4000,
    'padding_between' => 15,
    'closeable' => true,
    'z_index' => 99,
];
