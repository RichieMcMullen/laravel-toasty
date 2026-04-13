<?php

namespace Atomcoder\Toasty;

use Atomcoder\Toasty\Support\PackageConfig;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ToastyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel_toasty.php', PackageConfig::namespace());

        $this->app->singleton(ToastManager::class, function ($app): ToastManager {
            return new ToastManager($app['session.store']);
        });

        $this->app->singleton(ToastyFactory::class, function ($app): ToastyFactory {
            return new ToastyFactory($app->make(ToastManager::class));
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-toasty');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'laravel-toasty');

        Blade::directive('laravelToasty', static function (): string {
            return "<?php echo view('laravel-toasty::components.toasts')->render(); ?>";
        });

        $this->publishes([
            __DIR__.'/../config/laravel_toasty.php' => config_path('laravel_toasty.php'),
        ], 'laravel-toasty-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-toasty'),
        ], 'laravel-toasty-views');

        if (! PackageConfig::get('legacy_aliases', false)) {
            return;
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'toasty');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'toasty');

        Blade::directive('toasty', static function (): string {
            return "<?php echo view('laravel-toasty::components.toasts')->render(); ?>";
        });
    }
}
