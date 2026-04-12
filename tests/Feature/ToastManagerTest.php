<?php

namespace Atomcoder\Toasty\Tests\Feature;

use Atomcoder\Toasty\Tests\TestCase;

class ToastManagerTest extends TestCase
{
    public function test_it_flashes_success_toasts_to_the_session(): void
    {
        toasty()->success('Profile saved', 'Everything is up to date.');

        $toasts = session()->get(config('toasty.session_key'));

        $this->assertCount(1, $toasts);
        $this->assertSame('Profile saved', $toasts[0]['message']);
        $this->assertSame('Everything is up to date.', $toasts[0]['description']);
        $this->assertSame('success', $toasts[0]['type']);
        $this->assertSame('top-center', $toasts[0]['position']);
    }

    public function test_it_keeps_multiple_toasts_in_the_same_request(): void
    {
        toasty()->success('Saved');
        toasty()->warning('Heads up');

        $toasts = session()->get(config('toasty.session_key'));

        $this->assertCount(2, $toasts);
        $this->assertSame('Saved', $toasts[0]['message']);
        $this->assertSame('Heads up', $toasts[1]['message']);
        $this->assertSame('warning', $toasts[1]['type']);
    }

    public function test_it_can_flash_custom_html_toasts(): void
    {
        toasty()->html('<strong>Custom</strong>', [
            'message' => 'Ignored by the html renderer',
            'position' => 'bottom-right',
        ]);

        $toasts = session()->get(config('toasty.session_key'));

        $this->assertSame('<strong>Custom</strong>', $toasts[0]['html']);
        $this->assertSame('bottom-right', $toasts[0]['position']);
    }

    public function test_it_supports_like_and_bell_toast_types(): void
    {
        toasty()->like('Post liked', 'Your reaction was saved.');
        toasty()->bell('Reminder set', 'We will notify you tomorrow.');

        $toasts = session()->get(config('toasty.session_key'));

        $this->assertSame('like', $toasts[0]['type']);
        $this->assertSame('bell', $toasts[1]['type']);
    }

    public function test_it_normalizes_blank_descriptions_to_null(): void
    {
        toasty()->success('Project created', '   ');

        $toasts = session()->get(config('toasty.session_key'));

        $this->assertNull($toasts[0]['description']);
    }
}
