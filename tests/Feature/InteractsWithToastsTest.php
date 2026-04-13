<?php

namespace Atomcoder\Toasty\Tests\Feature;

use Atomcoder\Toasty\Concerns\InteractsWithToasts;
use Atomcoder\Toasty\Tests\TestCase;

class InteractsWithToastsTest extends TestCase
{
    public function test_it_dispatches_the_expected_browser_event_payload(): void
    {
        $component = new class
        {
            use InteractsWithToasts;

            public string $event = '';

            /**
             * @var array<string, mixed>
             */
            public array $payload = [];

            public function dispatch(string $event, mixed ...$payload): void
            {
                $this->event = $event;
                $this->payload = $payload;
            }

            public function trigger(): void
            {
                $this->dispatchLaravelToastySuccess('Saved', 'Changes stored.', [
                    'position' => 'top-right',
                ]);
            }
        };

        $component->trigger();

        $this->assertSame('laravel-toasty:notify', $component->event);
        $this->assertSame('Saved', $component->payload['message']);
        $this->assertSame('Changes stored.', $component->payload['description']);
        $this->assertSame('success', $component->payload['type']);
        $this->assertSame('top-right', $component->payload['position']);
    }
}
