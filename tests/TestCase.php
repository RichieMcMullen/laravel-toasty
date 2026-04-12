<?php

namespace Atomcoder\Toasty\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Atomcoder\Toasty\ToastyServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ToastyServiceProvider::class,
        ];
    }
}
