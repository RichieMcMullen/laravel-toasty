<?php

namespace Atomcoder\Toasty\Concerns;

use LogicException;
use Atomcoder\Toasty\Support\ToastPayload;

trait InteractsWithToasts
{
    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchToast(string $message, array $options = []): void
    {
        if (! method_exists($this, 'dispatch')) {
            throw new LogicException('The InteractsWithToasts trait requires a dispatch() method.');
        }

        $payload = ToastPayload::make($message, $options);

        $this->dispatch(
            (string) config('toasty.event_name', 'toasty-show'),
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
    protected function dispatchSuccessToast(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchToast($message, array_merge($options, [
            'type' => 'success',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchInfoToast(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchToast($message, array_merge($options, [
            'type' => 'info',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchWarningToast(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchToast($message, array_merge($options, [
            'type' => 'warning',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchDangerToast(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchToast($message, array_merge($options, [
            'type' => 'danger',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchLikeToast(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchToast($message, array_merge($options, [
            'type' => 'like',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchBellToast(string $message, ?string $description = null, array $options = []): void
    {
        $this->dispatchToast($message, array_merge($options, [
            'type' => 'bell',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchHtmlToast(string $html, array $options = []): void
    {
        $this->dispatchToast((string) ($options['message'] ?? ''), array_merge($options, [
            'html' => $html,
        ]));
    }
}
