<?php

namespace Atomcoder\Toasty\Tests\Feature;

use Atomcoder\Toasty\Tests\TestCase;

class BladeComponentTest extends TestCase
{
    public function test_the_blade_directive_renders_the_toast_stack(): void
    {
        $html = $this->blade('@toasty');

        $html->assertSee('window.ToastyComponent', false);
        $html->assertSee('x-teleport="body"', false);
        $html->assertSee('toasty-show', false);
        $html->assertSee('window.Toasty.activeMountId', false);
    }

    public function test_the_component_includes_flashed_toasts_in_the_payload(): void
    {
        toasty()->info('Welcome back', 'You have 3 new notifications.');

        $html = $this->blade('<x-toasty::toasts />');

        $html->assertSee('Welcome back', false);
        $html->assertSee('You have 3 new notifications.', false);
        $html->assertSee('window.toast', false);
    }

    public function test_the_component_includes_theme_configuration(): void
    {
        config()->set('toasty.theme', 'toasty');

        $html = $this->blade('<x-toasty::toasts />');

        $html->assertSee('linear-gradient(135deg, #ef4444, #b91c1c)', false);
        $html->assertSee('max_width', false);
    }
}
