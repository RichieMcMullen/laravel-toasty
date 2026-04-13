# Laravel Toasty

![Laravel Toasty](docs/images/header.png)

Toast notifications for Laravel `10` through `13` with collision-safe Blade, PHP, Livewire, and JavaScript APIs.

## What Changed

This package now defaults to uniquely named public APIs so it can live alongside Flux toasts or other toast packages without stepping on shared globals.

- Blade component namespace is now `<x-laravel-toasty::toasts />`
- Blade directive is now `@laravelToasty`
- PHP helper is now `laravel_toasty()`
- Livewire trait methods are now `dispatchLaravelToasty...()`
- Browser events are now `laravel-toasty:notify` and `laravel-toasty:layout`
- JavaScript API is now `window.LaravelToasty`
- Tailwind is no longer required for the package UI because the component ships with embedded, package-scoped CSS

## Features

- Laravel `10`, `11`, `12`, and `13` compatible
- Livewire `3` and `4` friendly
- Session-backed PHP toasts
- Immediate browser and Livewire toasts
- Bundled visual themes with style overrides
- Embedded package CSS, so no Tailwind content scanning is needed
- Optional legacy aliases for older installs that need a migration bridge

## Requirements

- PHP `8.1+`
- Laravel `10+`
- Alpine.js `3+`

Tailwind CSS is optional now. The toast UI no longer depends on your app compiling vendor Tailwind classes.

## Installation

```bash
composer require atomcoder/laravel-toasty
```

Optional: publish the config file.

```bash
php artisan vendor:publish --tag=laravel-toasty-config
```

Optional: publish the views if you want to restyle the package markup.

```bash
php artisan vendor:publish --tag=laravel-toasty-views
```

## Setup

Render the toast stack once in your main layout, usually near the end of `<body>`.

```blade
<x-laravel-toasty::toasts />
```

Or use the directive:

```blade
@laravelToasty
```

If you need per-layout overrides, use the component form:

```blade
<x-laravel-toasty::toasts
    position="bottom-right"
    layout="expanded"
    :duration="6000"
    theme="toasty"
/>
```

Make sure Alpine is available on the page. If your app already uses Alpine, you are done.

```blade
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

## Basic Usage

### PHP helper

Use `laravel_toasty()` anywhere in the normal request cycle.

```php
laravel_toasty()->success('Profile updated');

laravel_toasty()->info('Invoice sent', 'The customer will receive it shortly.');

laravel_toasty()->warning('Heads up', 'Billing details need attention.', [
    'position' => 'bottom-right',
    'duration' => 8000,
]);
```

Available methods:

- `laravel_toasty()->flash($message, $options = [])`
- `laravel_toasty()->success($message, $description = null, $options = [])`
- `laravel_toasty()->info($message, $description = null, $options = [])`
- `laravel_toasty()->warning($message, $description = null, $options = [])`
- `laravel_toasty()->danger($message, $description = null, $options = [])`
- `laravel_toasty()->like($message, $description = null, $options = [])`
- `laravel_toasty()->bell($message, $description = null, $options = [])`
- `laravel_toasty()->html($html, $options = [])`

### Facade

The Laravel alias is now `LaravelToasty`.

```php
use LaravelToasty;

LaravelToasty::success('Saved');
```

### JavaScript

Use the namespaced browser helper:

```html
<script>
    window.LaravelToasty.notify('Settings saved');

    window.LaravelToasty.notify('Deployment finished', {
        type: 'success',
        description: 'Everything is live.',
        position: 'top-right',
        duration: 5000,
    });

    window.LaravelToasty.layout('expanded');
</script>
```

There is also a lowercase alias:

```html
<script>
    window.laravelToasty.notify('Saved');
</script>
```

### Livewire

Use the trait when you want the toast immediately after a Livewire action.

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Atomcoder\Toasty\Concerns\InteractsWithToasts;

class EditProfile extends Component
{
    use InteractsWithToasts;

    public function save(): void
    {
        $this->dispatchLaravelToastySuccess(
            'Profile updated',
            'Your account details are now current.',
            ['position' => 'top-right']
        );
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
```

Available Livewire methods:

- `$this->dispatchLaravelToasty($message, $options = [])`
- `$this->dispatchLaravelToastySuccess($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyInfo($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyWarning($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyDanger($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyLike($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyBell($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyHtml($html, $options = [])`

## Options

Each toast accepts these options:

| Option | Type | Description |
| --- | --- | --- |
| `description` | `string|null` | Secondary text under the title |
| `type` | `default`, `success`, `info`, `warning`, `danger`, `like`, `bell` | Toast style and built-in icon |
| `position` | `top-left`, `top-center`, `top-right`, `bottom-left`, `bottom-center`, `bottom-right` | Stack position |
| `duration` | `int` | Auto-dismiss time in milliseconds. Use `0` to keep it open |
| `closeable` | `bool` | Whether to show the close button |
| `layout` | `default`, `expanded` | Switch the container layout when the toast is shown |
| `html` | `string|null` | Trusted custom HTML for the toast body |

## Configuration

Published config lives at `config/laravel_toasty.php`.

Important keys:

- `event_name`: browser event for new toasts, default `laravel-toasty:notify`
- `layout_event_name`: browser event for layout changes, default `laravel-toasty:layout`
- `session_key`: flashed toast storage key, default `laravel_toasty.toasts`
- `legacy_aliases`: opt-in shims for old Blade aliases and old JS globals
- `position`: default stack position
- `layout`: default stack layout
- `duration`: default auto-dismiss time
- `padding_between`: gap between expanded toasts
- `closeable`: default close button visibility
- `z_index`: stack z-index
- `theme`: active preset
- `themes`: bundled preset definitions
- `styles`: overrides merged into the active theme

### Themes

Bundled themes:

- `pines`: closest to the original Pines feel
- `toasty`: warmer gradients and deeper shadows
- `glass`: softer translucent cards

Override only what you need:

```php
'theme' => 'toasty',

'styles' => [
    'max_width' => '30rem',
    'base' => [
        'radius' => '1.5rem',
    ],
    'types' => [
        'success' => [
            'background' => 'linear-gradient(135deg, #4d7c0f, #166534)',
        ],
    ],
],
```

## Customization

If you want full control over the markup:

1. Publish the views.
2. Edit `resources/views/vendor/laravel-toasty/components/toasts.blade.php`.

The packaged component already includes its own CSS, so you do not need Tailwind just to keep the default design working.

## Legacy Migration

If you are upgrading an older install, these are the direct replacements:

| Old | New |
| --- | --- |
| `toasty()` | `laravel_toasty()` |
| `@toasty` | `@laravelToasty` |
| `<x-toasty::toasts />` | `<x-laravel-toasty::toasts />` |
| `window.Toasty` | `window.LaravelToasty` |
| `window.toast(...)` | `window.LaravelToasty.notify(...)` |
| `dispatchSuccessToast()` | `dispatchLaravelToastySuccess()` |
| `toasty-show` | `laravel-toasty:notify` |
| `toasty-set-layout` | `laravel-toasty:layout` |

If you need a temporary frontend migration bridge, set this in `config/laravel_toasty.php`:

```php
'legacy_aliases' => true,
```

That will restore:

- `@toasty`
- `<x-toasty::toasts />`
- `window.Toasty`
- `window.toast(...)`

The PHP helper is intentionally not restored, because the goal of this release is to eliminate generic global helper collisions.

## Notes

- Render the stack only once per page.
- Keep the stack in your top-level app layout if you use Livewire Navigate or a shared shell.
- Custom HTML toasts use `x-html`, so only pass trusted markup.
- The package now ships its own scoped CSS, so no Tailwind `@source` or `content` updates are required.

## Testing

```bash
composer test
```

## Credits

- [DevDojo Pines Toast](https://devdojo.com/pines/docs/toast) for the original toast interaction pattern

## License

MIT
