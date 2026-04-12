# Laravel Toasty

![Laravel Toasty](docs/images/header.png)

Toast notifications for Laravel `10` through `13`, with Blade, JavaScript, and Livewire-friendly APIs.

This package wraps the [Pines toast pattern](https://devdojo.com/pines/docs/toast) in a Laravel package so you can:

- render the toast stack once in Blade
- flash toasts from controllers, actions, middleware, jobs, or services
- dispatch toasts directly from Livewire `3` and `4`
- trigger toasts from JavaScript with `window.toast(...)`

## Features

- Laravel `10`, `11`, `12`, and `13` compatible
- Works with Livewire `3` and `4`
- Blade component and Blade directive rendering
- Session-backed PHP toast helpers
- Custom HTML toast support
- Configurable position, layout, duration, spacing, and browser event names
- Built-in `pines`, `toasty`, and `glass` visual presets with mergeable style overrides
- Based on the Pines toast interaction model and `window.toast()` API

## Requirements

- PHP `8.1+`
- Laravel `10+`
- Alpine.js `3+`
- Tailwind CSS

The package ships Blade markup that uses Tailwind utility classes. If your app does not scan vendor Blade files, the toast styles will not compile until you add the package view path to Tailwind.

## Installation

```bash
composer require atomcoder/laravel-toasty
```

Optional: publish the config file.

```bash
php artisan vendor:publish --tag=toasty-config
```

Optional: publish the views if you want to customize the default Pines-style markup.

```bash
php artisan vendor:publish --tag=toasty-views
```

## Setup

### 1. Render the toast stack once

Add the toast stack to your main layout, usually near the end of `<body>`.

```blade
<x-toasty::toasts />
```

Or use the Blade directive:

```blade
@toasty
```

If you want per-layout overrides, use the component form:

```blade
<x-toasty::toasts
    position="bottom-right"
    layout="expanded"
    :duration="6000"
    theme="toasty"
/>
```

### 2. Make sure Alpine is available

If your app already uses Alpine, you are done. Otherwise load Alpine once in your layout.

```blade
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

### 3. Make sure Tailwind sees the package views

If you do not have a `tailwind.config.js`, that is usually because your app is using Tailwind v4. In that case, use the `@source` option below and do not create a config file just for this package unless your app already uses one.

#### Tailwind v4

Add a source entry where you import your CSS:

```css
@source "../vendor/atomcoder/laravel-toasty/resources/views/**/*.blade.php";
```

#### Tailwind v3

Add the package view path to `content` in `tailwind.config.js`:

```js
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/atomcoder/laravel-toasty/resources/views/**/*.blade.php',
    ],
}
```

## Basic Usage

### Choose the right API

Use the API that matches where the toast is being triggered:

| Situation | Use |
| --- | --- |
| Controller, middleware, service, action class, redirect flow | `toasty()->...` |
| Livewire action and you want the toast immediately | `$this->dispatch...Toast()` |
| Browser-only interaction or Alpine/JS button | `toast(...)` or `window.Toasty.toast(...)` |
| Layout rendering | `<x-toasty::toasts />` or `@toasty` |

### Will `toasty()` work in normal Laravel code?

Yes. `toasty()` is a server-side helper backed by the session, so it works well in normal Laravel request code such as:

- controllers
- form actions
- middleware
- service classes called during a request
- anything else that has access to the current request/session

Example:

```php
toasty()->success('Profile updated');
```

### Will `toasty()` work in Livewire components?

Yes, but with an important caveat:

- `toasty()->...` inside Livewire is still session-based
- that means it is best for redirects or the next full page load
- if you want the toast to appear immediately after a Livewire action, use the Livewire trait methods instead

Immediate Livewire example:

```php
$this->dispatchSuccessToast('Profile updated');
```

Session/redirect style example inside Livewire:

```php
toasty()->success('Profile updated');

return $this->redirect(route('dashboard'));
```

### Will `toasty()` work inside Blade components?

Not as the main way to trigger interactive toasts.

Blade components are best used to render the toast stack:

```blade
<x-toasty::toasts />
```

If you want a click inside Blade or Alpine to show a toast immediately in the browser, use JavaScript:

```html
<button type="button" onclick="toast('Saved')">
    Show toast
</button>
```

### Session-based PHP helper

Use the global helper when you want to queue a toast from PHP for the current request cycle or the next rendered page.

Basic example:

```php
toasty()->success('Profile updated');
```

With a description:

```php
toasty()->info('Invoice sent', 'The customer will receive it shortly.');
```

With a custom position or duration:

```php
toasty()->warning('Heads up', 'Billing details need attention.', [
    'position' => 'bottom-right',
    'duration' => 8000,
]);
```

Available shortcut methods:

- `toasty()->flash($message, $options = [])`
- `toasty()->success($message, $description = null, $options = [])`
- `toasty()->info($message, $description = null, $options = [])`
- `toasty()->warning($message, $description = null, $options = [])`
- `toasty()->danger($message, $description = null, $options = [])`
- `toasty()->like($message, $description = null, $options = [])`
- `toasty()->bell($message, $description = null, $options = [])`
- `toasty()->html($html, $options = [])`

These helper methods are session-based. They are ideal for controller actions, redirects, and standard request/response flows.

### Use the facade

```php
use Toasty;

Toasty::success('Saved');
```

### Use JavaScript directly

The rendered toast stack also exposes a browser helper:

```html
<script>
    toast('Settings saved');

    toast('Deployment finished', {
        type: 'success',
        description: 'Everything is live.',
        position: 'top-right',
        duration: 5000,
    });
</script>
```

You can also change the stack layout at runtime:

```html
<script>
    window.Toasty.layout('expanded');
</script>
```

## Supported Options

Each toast accepts these options:

| Option | Type | Description |
| --- | --- | --- |
| `description` | `string|null` | Secondary text under the title |
| `type` | `default`, `success`, `info`, `warning`, `danger`, `like`, `bell` | Toast style and icon |
| `position` | `top-left`, `top-center`, `top-right`, `bottom-left`, `bottom-center`, `bottom-right` | Stack position |
| `duration` | `int` | Auto-dismiss time in milliseconds. Use `0` to keep it open |
| `closeable` | `bool` | Whether to show the close button |
| `layout` | `default`, `expanded` | Switch the container layout when the toast is shown |
| `html` | `string|null` | Trusted custom HTML for the toast body |

## Controller Examples

### Redirect back with a toast

```php
public function update(ProfileRequest $request)
{
    $request->user()->update($request->validated());

    toasty()->success('Profile updated', 'Your changes have been saved.');

    return redirect()->route('profile.edit');
}
```

### Queue multiple toasts in one request

```php
toasty()->success('Project created');
toasty()->info('Invite your team', 'You can do that from the members screen.');
```

### Custom HTML toast

```php
toasty()->html(<<<'HTML'
    <div class="relative flex items-start justify-center p-4">
        <div class="flex flex-col">
            <p class="text-sm font-medium text-gray-800">New Friend Request</p>
            <p class="mt-1 text-xs leading-none text-gray-800">Friend request from John Doe.</p>
        </div>
    </div>
HTML, [
    'position' => 'bottom-right',
    'duration' => 10000,
]);
```

`html` is rendered with `x-html`, so only pass trusted HTML.

## Livewire 3 and 4

Render the toast stack once in your main layout:

```blade
<x-toasty::toasts />
```

Then use the provided trait inside your Livewire component:

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
        // Save your data...

        $this->dispatchSuccessToast(
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

Use the Livewire trait when you want the toast immediately after the action completes without waiting for a full page reload or redirect.

Available Livewire helpers:

- `$this->dispatchToast($message, $options = [])`
- `$this->dispatchSuccessToast($message, $description = null, $options = [])`
- `$this->dispatchInfoToast($message, $description = null, $options = [])`
- `$this->dispatchWarningToast($message, $description = null, $options = [])`
- `$this->dispatchDangerToast($message, $description = null, $options = [])`
- `$this->dispatchLikeToast($message, $description = null, $options = [])`
- `$this->dispatchBellToast($message, $description = null, $options = [])`
- `$this->dispatchHtmlToast($html, $options = [])`

Because the frontend listens for browser events, the same stack works across Blade pages, redirects, and Livewire updates.

## Configuration

Published config:

```php
return [
    'event_name' => 'toasty-show',
    'layout_event_name' => 'toasty-set-layout',
    'session_key' => 'toasty.toasts',
    'position' => 'top-center',
    'layout' => 'default',
    'duration' => 4000,
    'padding_between' => 15,
    'closeable' => true,
    'z_index' => 99,
    'theme' => 'pines',
    'themes' => [
        // bundled presets...
    ],
    'styles' => [
        // optional overrides...
    ],
];
```

### What each config value does

- `event_name`: browser event used for new toasts
- `layout_event_name`: browser event used to switch between `default` and `expanded`
- `session_key`: where flashed toasts are stored
- `position`: default toast position
- `layout`: default layout
- `duration`: default auto-dismiss time in milliseconds
- `padding_between`: gap between expanded toasts
- `closeable`: default close button visibility
- `z_index`: z-index applied to the container
- `theme`: active visual preset, one of `pines`, `toasty`, or `glass`
- `themes`: bundled preset definitions
- `styles`: an override array merged into the active theme

### Available themes

- `pines`: light, clean, and closest to the original Pines look
- `toasty`: warm gradients and deeper shadows inspired by the promo artwork
- `glass`: cooler translucent cards with a soft glassmorphism feel

### Promo-style theme

The bundled `toasty` preset is meant to better match the package artwork:

- wider cards
- rounder corners
- deeper shadows
- warm glows
- full-surface success, warning, info, and danger gradients

If you want the lighter original look instead:

```php
'theme' => 'pines',
```

If you want a softer translucent look instead:

```php
'theme' => 'glass',
```

### Override only what you need

You can keep the `toasty` preset and override a few values:

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
        'warning' => [
            'title_color' => '#3b1d00',
        ],
    ],
],
```

## Blade API

### Default render

```blade
<x-toasty::toasts />
```

### Render with overrides

```blade
<x-toasty::toasts
    position="bottom-left"
    layout="expanded"
    :duration="7000"
    :padding-between-toasts="20"
    :closeable="false"
    :z-index="120"
    theme="toasty"
/>
```

### Render with inline style overrides

```blade
<x-toasty::toasts
    theme="toasty"
    :styles="[
        'max_width' => '30rem',
        'base' => ['radius' => '1.5rem'],
        'types' => [
            'danger' => [
                'background' => 'linear-gradient(135deg, #dc2626, #7f1d1d)',
            ],
        ],
    ]"
/>
```

### Directive render

```blade
@toasty
```

Use the component when you want to override props. Use the directive when the config defaults are enough.

## Customization

If you want to change the look and feel:

1. Publish the views:

```bash
php artisan vendor:publish --tag=toasty-views
```

2. Edit the published component:

`resources/views/vendor/toasty/components/toasts.blade.php`

That lets you keep the package API while fully restyling the markup.

## Notes

- Render the stack only once per page.
- If you use Livewire Navigate or a shared app shell, keep the toast stack in the top-level layout.
- The default browser events are namespaced as `toasty-show` and `toasty-set-layout` to avoid collisions with libraries like Flux.
- Custom HTML toasts are powerful, but they should only contain trusted markup.
- If you change `event_name`, use `window.Toasty.toast(..., { event: 'your-event' })` only when you need a one-off override. Otherwise the package automatically updates the global helper defaults from the rendered component.

## Testing

```bash
composer test
```

## Credits

- [DevDojo Pines Toast](https://devdojo.com/pines/docs/toast) for the original toast interaction pattern

## License

MIT
