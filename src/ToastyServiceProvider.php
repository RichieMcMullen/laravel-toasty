<?php

namespace Atomcoder\Toasty;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ToastyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/toasty.php', 'toasty');

        $this->app->singleton(ToastManager::class, function ($app): ToastManager {
            return new ToastManager($app['session.store']);
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'toasty');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'toasty');

        Blade::directive('toasty', static function (): string {
            return "<?php echo view('toasty::components.toasts')->render(); ?>";
        });

        $this->publishes([
            __DIR__.'/../config/toasty.php' => config_path('toasty.php'),
        ], 'toasty-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/toasty'),
        ], 'toasty-views');
    }
}
