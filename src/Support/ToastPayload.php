<?php

namespace Atomcoder\Toasty\Support;

use Illuminate\Support\Arr;

class ToastPayload
{
    /**
     * @var array<int, string>
     */
    protected const TYPES = [
        'default',
        'success',
        'info',
        'warning',
        'danger',
        'like',
        'bell',
    ];

    /**
     * @var array<int, string>
     */
    protected const POSITIONS = [
        'top-left',
        'top-center',
        'top-right',
        'bottom-left',
        'bottom-center',
        'bottom-right',
    ];

    /**
     * @var array<int, string>
     */
    protected const LAYOUTS = [
        'default',
        'expanded',
    ];

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public static function make(string $message = '', array $options = []): array
    {
        $payload = [
            'message' => $message,
            'description' => self::stringOrNull(Arr::get($options, 'description')),
            'type' => self::valueOrDefault(Arr::get($options, 'type'), self::TYPES, 'default'),
            'position' => self::valueOrDefault(
                Arr::get($options, 'position'),
                self::POSITIONS,
                (string) config('toasty.position', 'top-center')
            ),
            'html' => self::stringOrNull(Arr::get($options, 'html')),
            'duration' => max(0, (int) Arr::get($options, 'duration', config('toasty.duration', 4000))),
            'closeable' => (bool) Arr::get($options, 'closeable', config('toasty.closeable', true)),
        ];

        $layout = Arr::get($options, 'layout');

        if ($layout !== null) {
            $payload['layout'] = self::valueOrDefault(
                $layout,
                self::LAYOUTS,
                (string) config('toasty.layout', 'default')
            );
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function fromArray(array $payload): array
    {
        return self::make((string) Arr::get($payload, 'message', ''), Arr::except($payload, ['message']));
    }

    /**
     * @param  array<int, array<string, mixed>>  $toasts
     * @return array<int, array<string, mixed>>
     */
    public static function normalizeBatch(array $toasts): array
    {
        return array_values(array_map(
            static fn (array $toast): array => self::fromArray($toast),
            array_filter($toasts, static fn ($toast): bool => is_array($toast))
        ));
    }

    /**
     * @param  array<int, string>  $allowed
     */
    protected static function valueOrDefault(mixed $value, array $allowed, string $default): string
    {
        $value = is_string($value) ? $value : $default;

        return in_array($value, $allowed, true) ? $value : $default;
    }

    protected static function stringOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
