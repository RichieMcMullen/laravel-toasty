<?php

namespace Atomcoder\Toasty;

use LogicException;
use Atomcoder\Toasty\Support\PackageConfig;
use Atomcoder\Toasty\Support\ToastPayload;

class PendingToasty
{
    public function __construct(
        protected ToastManager $manager,
        protected mixed $target = null,
    ) {
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function flash(string $message, array $options = []): static
    {
        if ($this->shouldDispatchToBrowser()) {
            $this->dispatchToast($message, $options);

            return $this;
        }

        $this->manager->flash($message, $options);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function success(string $message, ?string $description = null, array $options = []): static
    {
        return $this->flash($message, array_merge($options, [
            'type' => 'success',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function info(string $message, ?string $description = null, array $options = []): static
    {
        return $this->flash($message, array_merge($options, [
            'type' => 'info',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function warning(string $message, ?string $description = null, array $options = []): static
    {
        return $this->flash($message, array_merge($options, [
            'type' => 'warning',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function danger(string $message, ?string $description = null, array $options = []): static
    {
        return $this->flash($message, array_merge($options, [
            'type' => 'danger',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function like(string $message, ?string $description = null, array $options = []): static
    {
        return $this->flash($message, array_merge($options, [
            'type' => 'like',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function bell(string $message, ?string $description = null, array $options = []): static
    {
        return $this->flash($message, array_merge($options, [
            'type' => 'bell',
            'description' => $description ?? $options['description'] ?? null,
        ]));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function html(string $html, array $options = []): static
    {
        return $this->flash((string) ($options['message'] ?? ''), array_merge($options, [
            'html' => $html,
        ]));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->manager->all();
    }

    public function clear(): void
    {
        $this->manager->clear();
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function dispatchToast(string $message, array $options = []): void
    {
        $payload = ToastPayload::make($message, $options);

        $this->dispatchTarget()->dispatch(
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

    protected function shouldDispatchToBrowser(): bool
    {
        return $this->target !== null;
    }

    protected function dispatchTarget(): object
    {
        if (! is_object($this->target) || ! method_exists($this->target, 'dispatch')) {
            throw new LogicException('A Livewire-like target with a dispatch() method is required for immediate browser toasts.');
        }

        return $this->target;
    }
}
