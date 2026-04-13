<?php

namespace Atomcoder\Toasty\Concerns;

use LogicException;
use Atomcoder\Toasty\Support\PackageConfig;
use Atomcoder\Toasty\Support\ToastPayload;

trait InteractsWithToasts
{
    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToasty(string $message, array $options = []): void
    {
        if (! method_exists($this, 'dispatch')) {
            throw new LogicException('The InteractsWithToasts trait requires a dispatch() method.');
        }

        $payload = ToastPayload::make($message, $options);

        $this->dispatch(
            (string) PackageConfig::get('event_name', 'laravel-toasty:notify'),
            message: $payload['message'],
            description: $payload['description'],
            type: $payload['type'],
            position: $payload['position'],
            html: $payload['html'],
            duration: $payload['duration'],
            closeable: $payload['closeable'],
            layout: $payload['layout'] ?? null,
        );
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastySuccess(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchLaravelToasty($message, array_merge($options, [
            'type' => 'success',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastyInfo(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchLaravelToasty($message, array_merge($options, [
            'type' => 'info',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastyWarning(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchLaravelToasty($message, array_merge($options, [
            'type' => 'warning',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastyDanger(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchLaravelToasty($message, array_merge($options, [
            'type' => 'danger',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastyLike(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchLaravelToasty($message, array_merge($options, [
            'type' => 'like',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastyBell(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchLaravelToasty($message, array_merge($options, [
            'type' => 'bell',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLaravelToastyHtml(string $html, array $options = []): void
    {
        $this->dispatchLaravelToasty((string) ($options['message'] ?? ''), array_merge($options, [
            'html' => $html,
        ]));
    }
}
