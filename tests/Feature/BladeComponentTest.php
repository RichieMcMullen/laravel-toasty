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
    }

    public function test_the_component_includes_flashed_toasts_in_the_payload(): void
    {
        toasty()->info('Welcome back', 'You have 3 new notifications.');

        $html = $this->blade('<x-toasty::toasts />');

        $html->assertSee('Welcome back', false);
        $html->assertSee('You have 3 new notifications.', false);
        $html->assertSee('window.toast', false);
    }
}
