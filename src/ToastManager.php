<?php

namespace Atomcoder\Toasty;

use Illuminate\Contracts\Session\Session;
use Atomcoder\Toasty\Support\PackageConfig;
use Atomcoder\Toasty\Support\ToastPayload;

class ToastManager
{
    public function __construct(
        protected Session $session
    ) {
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function flash(string $message, array $options = []): static
    {
        $toasts = $this->all();
        $toasts[] = ToastPayload::make($message, $options);

        $this->session->flash($this->sessionKey(), $toasts);

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
        return ToastPayload::normalizeBatch(
            (array) $this->session->get($this->sessionKey(), [])
        );
    }

    public function clear(): void
    {
        $this->session->forget($this->sessionKey());
    }

    protected function sessionKey(): string
    {
        return (string) PackageConfig::get('session_key', 'laravel_toasty.toasts');
    }
}
