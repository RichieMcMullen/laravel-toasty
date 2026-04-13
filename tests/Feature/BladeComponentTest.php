<?php

namespace Atomcoder\Toasty\Tests\Feature;

use Atomcoder\Toasty\Tests\TestCase;

class BladeComponentTest extends TestCase
{
    public function test_the_blade_directive_renders_the_toast_stack(): void
    {
        $html = $this->blade('@laravelToasty');

        $html->assertSee('window.LaravelToastyComponent', false);
        $html->assertSeeInOrder([
            'window.LaravelToastyComponent',
            'x-data="window.LaravelToastyComponent',
        ], false);
        $html->assertSee('x-teleport="body"', false);
        $html->assertSee('laravel-toasty:notify', false);
        $html->assertSee('window.LaravelToasty.activeMountId', false);
    }

    public function test_the_component_includes_flashed_toasts_in_the_payload_without_legacy_aliases(): void
    {
        laravel_toasty()->info('Welcome back', 'You have 3 new notifications.');

        $html = $this->blade('<x-laravel-toasty::toasts />');

        $html->assertSee('Welcome back', false);
        $html->assertSee('You have 3 new notifications.', false);
        $html->assertDontSee('window.toast', false);
        $html->assertDontSee('window.Toasty', false);
        $html->assertSee('window.LaravelToasty.notify', false);
    }

    public function test_the_component_includes_theme_configuration(): void
    {
        config()->set('laravel_toasty.theme', 'toasty');

        $html = $this->blade('<x-laravel-toasty::toasts />');

        $html->assertSee('linear-gradient(135deg, #ef4444, #b91c1c)', false);
        $html->assertSee('max_width', false);
    }

    public function test_legacy_aliases_are_only_rendered_when_enabled(): void
    {
        config()->set('laravel_toasty.legacy_aliases', true);

        $html = $this->blade('<x-laravel-toasty::toasts />');

        $html->assertSee('window.Toasty = window.LaravelToasty', false);
        $html->assertSee('window.toast = window.toast ||', false);
    }
}
