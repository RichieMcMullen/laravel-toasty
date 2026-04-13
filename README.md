# Laravel Toasty

[![Latest Version on Packagist](https://img.shields.io/packagist/v/atomcoder/laravel-toasty?style=flat-square)](https://packagist.org/packages/atomcoder/laravel-toasty)
[![Total Downloads](https://img.shields.io/packagist/dt/atomcoder/laravel-toasty?style=flat-square)](https://packagist.org/packages/atomcoder/laravel-toasty)
[![PHP Version](https://img.shields.io/packagist/php-v/atomcoder/laravel-toasty?style=flat-square)](https://packagist.org/packages/atomcoder/laravel-toasty)
[![Laravel](https://img.shields.io/badge/Laravel-10%20to%2013-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![License](https://img.shields.io/github/license/RichieMcMullen/laravel-toasty?style=flat-square)](LICENSE.md)
[![Latest Release](https://img.shields.io/github/v/release/RichieMcMullen/laravel-toasty?style=flat-square)](https://github.com/RichieMcMullen/laravel-toasty/releases)
[![Stars](https://img.shields.io/github/stars/RichieMcMullen/laravel-toasty?style=flat-square)](https://github.com/RichieMcMullen/laravel-toasty/stargazers)

![Laravel Toasty](docs/images/header.png)

Collision-safe toast notifications for Laravel `10` through `13`, designed for Blade, Alpine, Livewire, and standard Laravel request flows.

This package gives you a single toast system that can be triggered from PHP, JavaScript, or Livewire without conflicting with Flux toasts or other packages that also use generic toast names.

See [CHANGELOG.md](CHANGELOG.md) for package history.

## Table of Contents

- [Why This Package Exists](#why-this-package-exists)
- [What Makes This Version Different](#what-makes-this-version-different)
- [Feature Highlights](#feature-highlights)
- [Package Surface At A Glance](#package-surface-at-a-glance)
- [Requirements](#requirements)
- [Installation](#installation)
- [Installation By App Type](#installation-by-app-type)
- [Quick Start](#quick-start)
- [How Laravel Toasty Works](#how-laravel-toasty-works)
- [Rendering the Toast Stack](#rendering-the-toast-stack)
- [Blade Component Props](#blade-component-props)
- [PHP Usage](#php-usage)
- [Facade Usage](#facade-usage)
- [Livewire Usage](#livewire-usage)
- [JavaScript Usage](#javascript-usage)
- [Browser Event Contract](#browser-event-contract)
- [Blade and Alpine Usage](#blade-and-alpine-usage)
- [End-To-End Recipes](#end-to-end-recipes)
- [All Toast Options](#all-toast-options)
- [Toast Types](#toast-types)
- [Position Options](#position-options)
- [Layout Options](#layout-options)
- [HTML Toasts](#html-toasts)
- [Managing Multiple Toasts](#managing-multiple-toasts)
- [Configuration](#configuration)
- [Complete Config Example](#complete-config-example)
- [Themes and Styling](#themes-and-styling)
- [Customization](#customization)
- [Legacy Migration Guide](#legacy-migration-guide)
- [Troubleshooting](#troubleshooting)
- [Testing](#testing)
- [Credits](#credits)
- [License](#license)

## Why This Package Exists

Laravel apps often end up needing toast notifications from more than one place:

- controllers after a redirect
- middleware during a web request
- service classes that run inside the request lifecycle
- Livewire actions that should show feedback immediately
- browser-side Alpine or vanilla JavaScript interactions

Most toast libraries cover only one or two of those cases well. Laravel Toasty is built to cover all of them with one consistent API and one rendered toast stack.

It also solves a common integration problem: generic names like `toast()`, `@toast`, or unscoped browser events can collide with other packages. Laravel Toasty now defaults to namespaced APIs so it can coexist cleanly with Flux and similar UI packages.

## What Makes This Version Different

Laravel Toasty `2.x` intentionally moved to collision-safe names.

The public package surface is now:

- Blade component: `<x-laravel-toasty::toasts />`
- Blade directive: `@laravelToasty`
- PHP helper: `laravel_toasty()` and `laravel_toasty($this)`
- Laravel facade alias: `LaravelToasty` and `LaravelToasty::for($this)`
- Livewire trait methods: `dispatchLaravelToasty...()`
- Browser events: `laravel-toasty:notify` and `laravel-toasty:layout`
- JavaScript API: `window.LaravelToasty`

This version also no longer relies on your app compiling vendor Tailwind classes. The default component ships with embedded package-scoped CSS, so the default install works even if you are not scanning vendor Blade files in Tailwind.

## Feature Highlights

- namespaced APIs that do not collide with generic `toast()` helpers or Flux toasts
- one rendered stack that can receive toasts from PHP, Livewire, Alpine, or plain JavaScript
- no required Tailwind install step, no vendor content scanning, and no extra package CSS import
- session-backed redirect toasts for standard Laravel request flows
- immediate browser-event toasts for Livewire and JavaScript interactions
- built-in themes with a shallow override layer for app-specific design changes
- optional legacy aliases to help migrate older installs without permanently staying on generic names

## Package Surface At A Glance

| Surface | Name | Use it when |
| --- | --- | --- |
| Blade component | `<x-laravel-toasty::toasts />` | You want to render the stack and optionally override props inline |
| Blade directive | `@laravelToasty` | You want the simplest stack render using config defaults |
| Unified helper | `laravel_toasty()` or `laravel_toasty($this)` | You want one namespaced helper for both session toasts and immediate Livewire toasts |
| Facade alias | `LaravelToasty` or `LaravelToasty::for($this)` | You prefer a facade-style API with the same session-or-dispatch model |
| Livewire trait | `InteractsWithToasts` | You want the lower-level Livewire-specific dispatch methods directly on the component |
| JavaScript global | `window.LaravelToasty` | You want to trigger toasts from Alpine or browser-side code |
| Browser events | `laravel-toasty:notify`, `laravel-toasty:layout` | You want to dispatch directly to the stack without the helper APIs |
| Published config | `config/laravel_toasty.php` | You want to change defaults, theme, event names, or compatibility shims |

## Requirements

- PHP `8.1+`
- Laravel `10+`
- Alpine.js `3+`

Livewire is optional, but if you want to dispatch toasts directly from Livewire components you should install Livewire `3` or `4`.

Tailwind is optional. The package UI does not require Tailwind to render properly.

## Installation

Install the package with Composer:

```bash
composer require atomcoder/laravel-toasty
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag=laravel-toasty-config
```

Optionally publish the package views if you want to customize the default markup:

```bash
php artisan vendor:publish --tag=laravel-toasty-views
```

You do not need to:

- install a separate npm package for the default toast UI
- import a vendor CSS file into your app CSS
- add vendor view paths to Tailwind `content`
- add Tailwind `@source` directives just to make the shipped toast component work
- publish config or views unless you actually want to customize behavior

## Installation By App Type

### Blade or classic Laravel apps

This is the simplest setup:

1. `composer require atomcoder/laravel-toasty`
2. render `<x-laravel-toasty::toasts />` once in your layout
3. make sure Alpine is loaded
4. trigger toasts with `laravel_toasty()`

### Livewire apps

The installation is the same, but the preferred call is now the same helper or facade entry point you already use elsewhere:

- `laravel_toasty($this)->success(...)`
- `LaravelToasty::for($this)->success(...)`

That tells the package to dispatch immediately through the current Livewire component instead of flashing to the session.

### Alpine or JavaScript-heavy pages

You still render the same Blade stack once, then call `window.LaravelToasty.notify(...)` anywhere in the browser. This is a good fit for client-side widgets, copied-to-clipboard interactions, and custom browser-side flows.

### Existing Tailwind apps

You can keep using Tailwind in your app, but Laravel Toasty no longer depends on it. That means this package should work even if your Tailwind build never scans vendor Blade files.

### Apps migrating from an older release

If you are moving from the older generic API names, migrate to the namespaced helpers first:

- `toasty()` -> `laravel_toasty()`
- `@toasty` -> `@laravelToasty`
- `<x-toasty::toasts />` -> `<x-laravel-toasty::toasts />`
- `window.Toasty` -> `window.LaravelToasty`

If you need breathing room while updating templates, set `legacy_aliases` to `true` temporarily.

## Quick Start

1. Install the package.
2. Render the toast stack once in your main layout.
3. Make sure Alpine is loaded.
4. Trigger a toast from PHP, Livewire, or JavaScript.

### Step 1. Render the toast stack

Put this near the end of your main layout, usually just before `</body>`:

```blade
<x-laravel-toasty::toasts />
```

Or use the directive form:

```blade
@laravelToasty
```

### Step 2. Make sure Alpine is available

If your app already uses Alpine, you are done.

If not, add it once:

```blade
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

### Step 3. Trigger a toast

From PHP:

```php
laravel_toasty()->success('Profile updated');
```

From Livewire:

```php
laravel_toasty($this)->success('Profile updated');
```

From JavaScript:

```html
<script>
    window.LaravelToasty.notify('Profile updated');
</script>
```

## How Laravel Toasty Works

Laravel Toasty supports three delivery modes.

### Architecture overview

Regardless of where a toast starts, the package tries to normalize the payload into one consistent shape before rendering it.

1. Helper or facade calls with no target normalize the payload and flash it into the session.
2. Helper or facade calls with a Livewire-like target normalize the payload and dispatch a browser event immediately.
3. The Livewire trait is still available and dispatches the same browser event directly from the component.
4. JavaScript calls dispatch the same browser event directly from the browser.
5. The rendered Blade stack reads initial session toasts on page load and listens for future browser events.
6. The Alpine runtime owns timers, stacking, hover expansion, close behavior, layout changes, and theme application.

### 1. Session-backed PHP toasts

When you call `laravel_toasty()->success(...)`, the package stores normalized toast payloads in the session using the configured session key.

Those toasts are then read by the rendered toast stack on the next page render.

This is ideal for:

- controller redirects
- standard form submissions
- middleware inside the `web` stack
- request-time service classes

### 2. Targeted Livewire browser-event toasts

When you call `laravel_toasty($this)->success(...)` or `LaravelToasty::for($this)->success(...)`, the package dispatches a browser event from your Livewire component with the normalized toast payload.

The rendered toast stack listens for that browser event and shows the toast immediately.

This is ideal for:

- instant Livewire save notifications
- validation-adjacent feedback
- real-time UI interactions with no redirect

The `InteractsWithToasts` trait remains available if you prefer dedicated Livewire methods like `dispatchLaravelToastySuccess(...)`, but it is no longer the only immediate-dispatch option.

### 3. Direct JavaScript toasts

When you call `window.LaravelToasty.notify(...)`, the package dispatches the same namespaced browser event the stack already listens for.

This is ideal for:

- Alpine button clicks
- custom browser-side flows
- vanilla JavaScript interactions
- integrations with client-side widgets

### The render-once rule

Render the toast stack once per page.

That is important because the toast stack owns:

- viewport positioning
- stacking behavior
- hover expansion
- runtime browser event listeners
- duplicate mount protection

If you render it more than once, only the first mount should be considered the real owner of the stack.

## Rendering the Toast Stack

The package ships one anonymous Blade component:

```blade
<x-laravel-toasty::toasts />
```

And one Blade directive:

```blade
@laravelToasty
```

Use the component when you want to override props inline. Use the directive when your config defaults are enough.

### Basic render

```blade
<x-laravel-toasty::toasts />
```

### Render with inline overrides

```blade
<x-laravel-toasty::toasts
    position="bottom-right"
    layout="expanded"
    :duration="7000"
    :padding-between-toasts="20"
    :closeable="false"
    :z-index="120"
    theme="toasty"
/>
```

### Render with style overrides

```blade
<x-laravel-toasty::toasts
    theme="glass"
    :styles="[
        'max_width' => '30rem',
        'base' => [
            'radius' => '1.5rem',
        ],
        'types' => [
            'danger' => [
                'background' => 'linear-gradient(135deg, #dc2626, #7f1d1d)',
            ],
        ],
    ]"
/>
```

### Recommended placement

Use your main app layout, not a nested child component:

```blade
<!doctype html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{ $slot }}

    <x-laravel-toasty::toasts />
</body>
</html>
```

## Blade Component Props

The component accepts a set of render-time props that mirror the package config. These are useful when you want page-specific behavior without changing the global defaults.

| Blade prop | Internal prop | Type | Purpose |
| --- | --- | --- | --- |
| `position` | `position` | `string` | Default stack position for that mount |
| `layout` | `layout` | `string` | Default stack layout for that mount |
| `:duration` | `duration` | `int` | Default auto-dismiss time in milliseconds |
| `:padding-between-toasts` | `paddingBetweenToasts` | `int` | Vertical gap between cards in expanded layout |
| `event-name` | `eventName` | `string` | Browser event name this stack listens to for toast notifications |
| `layout-event-name` | `layoutEventName` | `string` | Browser event name this stack listens to for layout changes |
| `:closeable` | `closeable` | `bool` | Whether close buttons are shown by default |
| `:z-index` | `zIndex` | `int` | Z-index used for the stack container |
| `theme` | `theme` | `string` | Bundled theme name to use |
| `:styles` | `styles` | `array` | Theme override array merged into the selected theme |
| `:legacy-aliases` | `legacyAliases` | `bool` | Enables old frontend aliases for migration support |

### Important Blade naming note

The component itself uses camelCase prop names internally, but when you pass them in Blade you should use normal Blade attribute syntax:

- `paddingBetweenToasts` becomes `padding-between-toasts`
- `eventName` becomes `event-name`
- `layoutEventName` becomes `layout-event-name`
- `zIndex` becomes `z-index`
- `legacyAliases` becomes `legacy-aliases`

### Custom event names per mount

If you want a stack that listens to different browser events than the global defaults, you can override them inline:

```blade
<x-laravel-toasty::toasts
    event-name="admin:toast"
    layout-event-name="admin:toast-layout"
/>
```

Your JavaScript or Livewire code must dispatch to those same event names for that custom mount to receive them.

## PHP Usage

The unified helper is:

```php
laravel_toasty()
```

If you pass no argument, the helper uses the session-backed transport.

If you pass a Livewire-like component instance that exposes `dispatch()`, the helper uses immediate browser-event dispatch:

```php
laravel_toasty($this)
```

This gives you one helper entry point for both standard Laravel requests and Livewire component actions.

### Available PHP methods

- `laravel_toasty()->flash($message, $options = [])`
- `laravel_toasty()->success($message, $description = null, $options = [])`
- `laravel_toasty()->info($message, $description = null, $options = [])`
- `laravel_toasty()->warning($message, $description = null, $options = [])`
- `laravel_toasty()->danger($message, $description = null, $options = [])`
- `laravel_toasty()->like($message, $description = null, $options = [])`
- `laravel_toasty()->bell($message, $description = null, $options = [])`
- `laravel_toasty()->html($html, $options = [])`
- `laravel_toasty()->all()`
- `laravel_toasty()->clear()`

The same methods are also available through a targeted Livewire call:

- `laravel_toasty($this)->success(...)`
- `laravel_toasty($this)->info(...)`
- `laravel_toasty($this)->warning(...)`
- `laravel_toasty($this)->danger(...)`
- `laravel_toasty($this)->like(...)`
- `laravel_toasty($this)->bell(...)`
- `laravel_toasty($this)->html(...)`

### Basic examples

```php
laravel_toasty()->success('Saved');

laravel_toasty()->info('Invoice sent', 'The customer will receive it shortly.');

laravel_toasty()->warning('Heads up', 'Billing details need attention.', [
    'position' => 'bottom-right',
    'duration' => 8000,
]);

laravel_toasty($this)->success('Saved without leaving the page');
```

### Generic flash method

Use `flash()` if you want full control over the payload:

```php
laravel_toasty()->flash('Maintenance scheduled', [
    'description' => 'Downtime begins at midnight UTC.',
    'type' => 'warning',
    'position' => 'top-right',
    'duration' => 0,
    'closeable' => true,
    'layout' => 'expanded',
]);
```

### Controller examples

#### Redirect after update

```php
public function update(ProfileRequest $request)
{
    $request->user()->update($request->validated());

    laravel_toasty()->success(
        'Profile updated',
        'Your changes have been saved.'
    );

    return redirect()->route('profile.edit');
}
```

#### Multiple toasts in one request

```php
public function store(ProjectRequest $request)
{
    $project = Project::create($request->validated());

    laravel_toasty()->success('Project created');
    laravel_toasty()->info('Invite your team', 'You can add members from the project settings page.');

    return redirect()->route('projects.show', $project);
}
```

#### Custom options

```php
public function destroy(Project $project)
{
    $project->delete();

    laravel_toasty()->danger('Project deleted', 'This action cannot be undone.', [
        'position' => 'bottom-left',
        'duration' => 6000,
    ]);

    return redirect()->route('projects.index');
}
```

### Middleware example

```php
public function handle($request, Closure $next)
{
    if ($request->user()?->is_impersonating) {
        laravel_toasty()->warning(
            'Impersonation active',
            'You are currently browsing as another user.',
            ['duration' => 0]
        );
    }

    return $next($request);
}
```

### Service class example

```php
class BillingNotifier
{
    public function remindAboutExpiredCard(): void
    {
        laravel_toasty()->warning(
            'Payment method expired',
            'Please update your billing details.',
            ['position' => 'top-right']
        );
    }
}
```

### HTML toast example

Use this when you need fully custom trusted markup:

```php
laravel_toasty()->html(<<<'HTML'
    <div style="padding: 1rem;">
        <strong>Deployment complete</strong>
        <div style="margin-top: 0.5rem; opacity: 0.8;">
            Version 2.0.0 is now live.
        </div>
    </div>
HTML, [
    'position' => 'bottom-right',
    'duration' => 10000,
]);
```

`html` is rendered with `x-html`, so only pass trusted markup.

### Inspecting and clearing queued toasts

```php
$toasts = laravel_toasty()->all();

if (count($toasts) > 5) {
    laravel_toasty()->clear();
}
```

`all()` and `clear()` work against the server-side session queue. They are useful for redirect-based request flows, but they do not inspect the already-mounted browser stack in a Livewire page.

### When not to use the PHP helper

Do not treat `laravel_toasty()` as a general background-job notification system.

With no target, it is session-backed, so it is best used during the web request lifecycle:

- web controllers
- form submissions
- redirect flows
- middleware in the `web` stack
- request-scoped service usage

If you need immediate feedback inside Livewire, use `laravel_toasty($this)` or `LaravelToasty::for($this)`.

## Facade Usage

Laravel registers the facade alias `LaravelToasty`.

### Using the alias

```php
use LaravelToasty;

LaravelToasty::success('Saved');
LaravelToasty::warning('Heads up', 'Please review the account settings.');
```

For immediate Livewire dispatch, target the current component:

```php
use LaravelToasty;

LaravelToasty::for($this)->info('Welcome back');
LaravelToasty::for($this)->success('Profile updated', 'Changes saved instantly.');
```

### Using the facade class directly

```php
use Atomcoder\Toasty\Facades\LaravelToasty;

LaravelToasty::info('Welcome back');
```

## Livewire Usage

The preferred Livewire API is now the same unified helper or facade entry point, just with the current component passed in.

### Preferred unified helper in Livewire

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class EditProfile extends Component
{
    public function save(): void
    {
        laravel_toasty($this)->success(
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

### Preferred unified facade in Livewire

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use LaravelToasty;

class EditProfile extends Component
{
    public function save(): void
    {
        LaravelToasty::for($this)->success(
            'Profile updated',
            'Your account details are now current.'
        );
    }
}
```

### Trait alternative

The `InteractsWithToasts` trait still works and is fully supported. It is useful if you want dedicated Livewire-specific method names directly on the component.

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
            'Your account details are now current.'
        );
    }
}
```

### Available Livewire methods

- `$this->dispatchLaravelToasty($message, $options = [])`
- `$this->dispatchLaravelToastySuccess($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyInfo($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyWarning($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyDanger($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyLike($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyBell($message, $description = null, $options = [])`
- `$this->dispatchLaravelToastyHtml($html, $options = [])`

### Livewire examples for each type

```php
laravel_toasty($this)->info('Draft saved');
laravel_toasty($this)->warning('Storage almost full');
laravel_toasty($this)->danger('Delete failed', 'Please try again.');
laravel_toasty($this)->like('Post liked');
laravel_toasty($this)->bell('Reminder set', 'We will notify you tomorrow.');
```

### Livewire HTML example

```php
laravel_toasty($this)->html(
    '<div style="padding:1rem;"><strong>Invite sent</strong><div style="margin-top:.5rem;">The user has been emailed.</div></div>',
    ['position' => 'bottom-right']
);
```

### Redirect flows vs immediate Livewire dispatch

Use immediate dispatch when the user stays on the current Livewire page:

```php
laravel_toasty($this)->success('Saved');
```

Use the session-backed helper if your Livewire action redirects and you want the next page render to show the toast:

```php
laravel_toasty()->success('Saved');

return $this->redirect(route('dashboard'));
```

### Important Livewire note

`laravel_toasty($this)` and `LaravelToasty::for($this)` require a target object with a `dispatch()` method. In practice that means a Livewire component or another compatible object exposing the same browser-event dispatching API.

## JavaScript Usage

The browser API is:

```js
window.LaravelToasty
```

There is also a lowercase alias:

```js
window.laravelToasty
```

### Available JavaScript methods

- `window.LaravelToasty.notify(message, options = {})`
- `window.LaravelToasty.toast(message, options = {})`
- `window.LaravelToasty.layout(layout = 'expanded', eventName = null)`
- `window.LaravelToasty.setLayout(layout = 'expanded', eventName = null)`

`notify()` and `toast()` are equivalent. `toast()` exists mostly as a convenience alias under the namespaced object.

### Basic JavaScript examples

```html
<script>
    window.LaravelToasty.notify('Settings saved');

    window.LaravelToasty.notify('Deployment finished', {
        type: 'success',
        description: 'Everything is live.',
        position: 'top-right',
        duration: 5000,
    });
</script>
```

### All built-in type examples

```html
<script>
    window.LaravelToasty.notify('Default toast');
    window.LaravelToasty.notify('Success toast', { type: 'success' });
    window.LaravelToasty.notify('Info toast', { type: 'info' });
    window.LaravelToasty.notify('Warning toast', { type: 'warning' });
    window.LaravelToasty.notify('Danger toast', { type: 'danger' });
    window.LaravelToasty.notify('Like toast', { type: 'like' });
    window.LaravelToasty.notify('Bell toast', { type: 'bell' });
</script>
```

### Position and duration examples

```html
<script>
    window.LaravelToasty.notify('Top left', {
        position: 'top-left',
    });

    window.LaravelToasty.notify('Bottom center', {
        position: 'bottom-center',
        duration: 7000,
    });

    window.LaravelToasty.notify('Persistent warning', {
        type: 'warning',
        duration: 0,
        closeable: true,
    });
</script>
```

### Layout examples

```html
<script>
    window.LaravelToasty.notify('Expanded stack', {
        layout: 'expanded',
    });

    window.LaravelToasty.layout('expanded');
    window.LaravelToasty.setLayout('default');
</script>
```

### HTML example

```html
<script>
    window.LaravelToasty.notify('', {
        html: `
            <div style="padding: 1rem;">
                <strong>Custom browser toast</strong>
                <div style="margin-top: .5rem;">Rendered with trusted HTML.</div>
            </div>
        `,
        position: 'bottom-right'
    });
</script>
```

### One-off custom event example

Usually you should stick to the configured default event names. If you need a one-off custom event:

```html
<script>
    window.LaravelToasty.notify('Custom event example', {
        event: 'my-app:toast',
    });
</script>
```

To make that useful, your rendered stack must also listen for the same event name through component props or config.

## Browser Event Contract

The stack ultimately listens for browser events. The namespaced helper APIs are convenience wrappers around that event contract.

### Notify event payload

The default notify event is:

```text
laravel-toasty:notify
```

Its `detail` payload can contain:

- `message`
- `description`
- `type`
- `position`
- `html`
- `duration`
- `closeable`
- `layout`

Direct browser event example:

```html
<script>
    window.dispatchEvent(new CustomEvent('laravel-toasty:notify', {
        detail: {
            message: 'Imported successfully',
            description: '34 records were synced.',
            type: 'success',
            position: 'top-right',
            duration: 6000,
            closeable: true,
            layout: 'expanded',
        }
    }));
</script>
```

### Layout event payload

The default layout event is:

```text
laravel-toasty:layout
```

Its `detail` payload should contain:

- `layout`

Example:

```html
<script>
    window.dispatchEvent(new CustomEvent('laravel-toasty:layout', {
        detail: {
            layout: 'expanded',
        }
    }));
</script>
```

If you change the configured event names or override them on the component, dispatch the new event names instead.

## Blade and Alpine Usage

### Alpine click example

```blade
<button
    type="button"
    x-on:click="window.LaravelToasty.notify('Saved from Alpine', {
        type: 'success',
        description: 'The client-side action completed.'
    })"
>
    Save
</button>
```

### Blade button example

```blade
<button
    type="button"
    onclick="window.LaravelToasty.notify('Project created', {
        type: 'success',
        description: 'Your new project is ready.'
    })"
>
    Show toast
</button>
```

### Alpine layout toggle example

```blade
<button type="button" x-on:click="window.LaravelToasty.layout('expanded')">
    Expand Toast Stack
</button>

<button type="button" x-on:click="window.LaravelToasty.layout('default')">
    Collapse Toast Stack
</button>
```

## End-To-End Recipes

These examples show how the package behaves in real application flows, not just isolated API calls.

### Redirect after a traditional form post

```php
public function store(ProjectRequest $request)
{
    $project = Project::create($request->validated());

    laravel_toasty()->success(
        'Project created',
        'You can now invite your team and configure billing.'
    );

    return redirect()->route('projects.show', $project);
}
```

Why this works well: the helper flashes the toast into the session, then the destination page render consumes and shows it.

### Immediate Livewire save confirmation with no redirect

```php
use Livewire\Component;

class EditProfile extends Component
{
    public function save(): void
    {
        auth()->user()->update([
            'name' => $this->name,
        ]);

        laravel_toasty($this)->success(
            'Profile updated',
            'Your changes were saved immediately.'
        );
    }
}
```

Why this works well: the same helper API now targets the current Livewire component and dispatches a browser event immediately, so there is no need to wait for a redirect or full page reload.

### Livewire action that redirects to another page

```php
use Livewire\Component;

class CreateWorkspace extends Component
{
    public function save()
    {
        $workspace = auth()->user()->workspaces()->create([
            'name' => $this->name,
        ]);

        laravel_toasty()->success(
            'Workspace created',
            'You have been taken to the new workspace.'
        );

        return $this->redirect(route('workspaces.show', $workspace));
    }
}
```

Why this works well: because the user is leaving the current page anyway, the session helper is the right transport.

### Alpine-only interaction

```blade
<button
    type="button"
    x-on:click="
        navigator.clipboard.writeText('INV-2026-0042');
        window.LaravelToasty.notify('Invoice number copied', {
            type: 'info',
            description: 'Paste it into your accounting tool.',
            position: 'bottom-right'
        });
    "
>
    Copy Invoice Number
</button>
```

Why this works well: the interaction is entirely client-side, so there is no need to involve PHP or Livewire.

### Persistent warning banner-style toast

```php
laravel_toasty()->warning(
    'Trial ending soon',
    'Upgrade your subscription to avoid interruption.',
    [
        'duration' => 0,
        'closeable' => true,
        'layout' => 'expanded',
        'position' => 'top-center',
    ]
);
```

Why this works well: `duration => 0` makes the toast persistent until the user closes it.

### Rich HTML call-to-action toast

```php
laravel_toasty()->html(<<<'HTML'
    <div style="padding: 1rem;">
        <div style="font-weight: 700;">New integration available</div>
        <div style="margin-top: .5rem; opacity: .8;">
            Connect Slack to send deployment notifications.
        </div>
        <div style="margin-top: .75rem;">
            <a href="/settings/integrations" style="font-weight: 600;">Open integrations</a>
        </div>
    </div>
HTML, [
    'position' => 'bottom-right',
    'duration' => 0,
]);
```

Why this works well: HTML toasts let you present richer UI when a title and description are not enough. Only use trusted markup.

## All Toast Options

Each toast accepts these options regardless of whether you trigger it from PHP, Livewire, or JavaScript.

| Option | Type | Description |
| --- | --- | --- |
| `description` | `string|null` | Secondary text shown below the title |
| `type` | `default`, `success`, `info`, `warning`, `danger`, `like`, `bell` | Determines icon and theme-specific styling |
| `position` | `top-left`, `top-center`, `top-right`, `bottom-left`, `bottom-center`, `bottom-right` | Controls the toast stack location |
| `duration` | `int` | Auto-dismiss time in milliseconds. Use `0` to keep the toast open |
| `closeable` | `bool` | Whether the close button is shown |
| `layout` | `default`, `expanded` | Switches the stack between collapsed and expanded modes |
| `html` | `string|null` | Trusted custom HTML rendered as the toast body |

### Normalization behavior

The package normalizes incoming toast data:

- invalid `type` values fall back to `default`
- invalid `position` values fall back to the configured default position
- invalid `layout` values fall back to the configured default layout
- blank descriptions are normalized to `null`
- `duration` is normalized to a non-negative integer

## Toast Types

Built-in toast types:

- `default`
- `success`
- `info`
- `warning`
- `danger`
- `like`
- `bell`

These types affect:

- icon
- theme type overrides
- title color
- description color
- icon badge styling
- close button styling

## Position Options

Valid positions:

- `top-left`
- `top-center`
- `top-right`
- `bottom-left`
- `bottom-center`
- `bottom-right`

Examples:

```php
laravel_toasty()->success('Top right', null, ['position' => 'top-right']);
laravel_toasty()->success('Bottom center', null, ['position' => 'bottom-center']);
```

```js
window.LaravelToasty.notify('Bottom left', { position: 'bottom-left' });
```

## Layout Options

Two layout modes are supported:

- `default`
- `expanded`

### `default`

This is the compact stack. Toasts overlap visually and expand on hover.

### `expanded`

This fully expands the stack and gives each toast its own vertical space.

Examples:

```php
laravel_toasty()->info('Expanded', 'This toast opens the stack expanded.', [
    'layout' => 'expanded',
]);
```

```js
window.LaravelToasty.layout('expanded');
window.LaravelToasty.layout('default');
```

## HTML Toasts

Custom HTML toasts are useful when you need richer markup than a simple title and description.

PHP:

```php
laravel_toasty()->html(<<<'HTML'
    <div style="padding: 1rem;">
        <strong>Welcome aboard</strong>
        <div style="margin-top: .5rem; opacity: .8;">
            Your account has been activated successfully.
        </div>
    </div>
HTML);
```

Livewire:

```php
$this->dispatchLaravelToastyHtml(
    '<div style="padding: 1rem;"><strong>Invite sent</strong></div>'
);
```

JavaScript:

```js
window.LaravelToasty.notify('', {
    html: '<div style="padding: 1rem;"><strong>Custom toast</strong></div>',
});
```

Use only trusted HTML, because the package renders it with `x-html`.

## Managing Multiple Toasts

Multiple toasts can be queued in the same request:

```php
laravel_toasty()->success('Project created');
laravel_toasty()->info('Invite your team');
laravel_toasty()->warning('Remember to configure billing');
```

The rendered stack will:

- place newer toasts at the top of the array
- stack them visually
- expand on hover in default mode
- honor per-toast position overrides

## Configuration

Publish the config if you want to customize defaults:

```bash
php artisan vendor:publish --tag=laravel-toasty-config
```

The published file is:

```php
config/laravel_toasty.php
```

## Complete Config Example

This is what a realistic customized config can look like:

```php
return [
    'event_name' => 'laravel-toasty:notify',
    'layout_event_name' => 'laravel-toasty:layout',
    'session_key' => 'laravel_toasty.toasts',
    'legacy_aliases' => false,

    'position' => 'top-center',
    'layout' => 'default',
    'duration' => 4000,
    'padding_between' => 15,
    'closeable' => true,
    'z_index' => 99,

    'theme' => 'toasty',

    'styles' => [
        'max_width' => '30rem',
        'base' => [
            'radius' => '1.25rem',
            'font_family' => 'ui-sans-serif, system-ui, sans-serif',
        ],
        'types' => [
            'success' => [
                'background' => 'linear-gradient(135deg, #166534, #14532d)',
            ],
            'danger' => [
                'background' => 'linear-gradient(135deg, #dc2626, #7f1d1d)',
            ],
        ],
    ],
];
```

The bundled `themes` array is intentionally omitted in that example for readability. In the actual package config it contains the built-in `pines`, `toasty`, and `glass` presets.

### Main config keys

- `event_name`
- `layout_event_name`
- `session_key`
- `legacy_aliases`
- `position`
- `layout`
- `duration`
- `padding_between`
- `closeable`
- `z_index`
- `theme`
- `themes`
- `styles`

### What each config value does

#### `event_name`

The browser event used to show a toast.

Default:

```php
'event_name' => 'laravel-toasty:notify',
```

#### `layout_event_name`

The browser event used to change the stack layout.

Default:

```php
'layout_event_name' => 'laravel-toasty:layout',
```

#### `session_key`

The session key where queued PHP toasts are stored.

Default:

```php
'session_key' => 'laravel_toasty.toasts',
```

#### `legacy_aliases`

Enables temporary frontend compatibility shims for older installs.

Default:

```php
'legacy_aliases' => false,
```

When enabled, the package also registers:

- `@toasty`
- `<x-toasty::toasts />`
- `window.Toasty`
- `window.toast(...)`

This setting is intentionally for migration support, not long-term recommended use.

#### `position`

Default stack position when a toast does not provide one.

#### `layout`

Default stack layout when a toast does not provide one.

#### `duration`

Default auto-dismiss duration in milliseconds.

#### `padding_between`

Spacing between toasts in expanded mode.

#### `closeable`

Whether to show the close button by default.

#### `z_index`

The z-index applied to the toast stack container.

#### `theme`

The active visual theme name.

#### `themes`

The full set of built-in theme definitions.

#### `styles`

Per-app override values merged into the selected theme.

### How theme resolution works

At render time, the component builds its final theme in this order:

1. start with the bundled `pines` preset as a safe fallback
2. merge in the configured or requested theme preset
3. merge in your top-level `styles` overrides
4. if you passed `:styles` directly to the component, those values are what get merged for that specific render

That approach lets you override only a few keys without copying an entire theme definition.

## Themes and Styling

Laravel Toasty includes three bundled themes:

- `pines`
- `toasty`
- `glass`

### `pines`

Light, clean, and closest to the original Pines feel.

### `toasty`

Warm gradients, richer depth, and a more promotional look.

### `glass`

Translucent, softer, and more glassmorphic.

### Change the active theme

```php
'theme' => 'toasty',
```

### Override only what you need

```php
'theme' => 'toasty',

'styles' => [
    'max_width' => '30rem',
    'base' => [
        'radius' => '1.5rem',
        'font_family' => 'ui-sans-serif, system-ui, sans-serif',
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

### Render-time style overrides

You do not have to put every style change in config. You can pass `styles` directly to the Blade component:

```blade
<x-laravel-toasty::toasts
    theme="glass"
    :styles="[
        'base' => [
            'radius' => '1.25rem',
        ],
        'types' => [
            'info' => [
                'background' => 'linear-gradient(135deg, rgba(59,130,246,.9), rgba(29,78,216,.85))',
            ],
        ],
    ]"
/>
```

## Customization

If you want to fully control the markup:

```bash
php artisan vendor:publish --tag=laravel-toasty-views
```

That publishes the views to:

```text
resources/views/vendor/laravel-toasty
```

Then edit:

```text
resources/views/vendor/laravel-toasty/components/toasts.blade.php
```

### Important note about published views

Published vendor views override package updates.

That means if you publish the package views and later update the package, your app will continue using your published copy until you manually update or remove it.

This is especially important when troubleshooting stale markup.

## Legacy Migration Guide

The old names and the new names map like this:

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

### Temporary compatibility mode

If you need a short migration bridge for frontend aliases:

```php
'legacy_aliases' => true,
```

This restores the old Blade and JavaScript aliases, but the recommended long-term usage is still the namespaced API.

## Troubleshooting

### The toast stack is not showing at all

Check:

- Alpine is loaded
- the stack is rendered once
- your layout actually includes `<x-laravel-toasty::toasts />`

### My Livewire action runs, but I do not see a toast

Check:

- you are calling `laravel_toasty($this)` or `LaravelToasty::for($this)` from the Livewire component, or you are using `InteractsWithToasts`
- the stack is mounted in the main layout
- you are using an immediate-dispatch API rather than an untargeted session call when you stay on the same page

### I updated the package but still see old markup or old runtime errors

Look for stale published views:

```bash
rg -n "window\\.LaravelToastyComponent|window\\.ToastyComponent" resources/views vendor
```

Then clear caches:

```bash
php artisan optimize:clear
composer clear-cache
```

If you previously published the package view, update or remove:

```text
resources/views/vendor/laravel-toasty/components/toasts.blade.php
resources/views/vendor/toasty/components/toasts.blade.php
```

### I am using Livewire and seeing weird stale markup issues

Verify the installed vendor view really matches the current package:

```bash
sed -n '1,40p' vendor/atomcoder/laravel-toasty/resources/views/components/toasts.blade.php
```

The current version uses `data-laravel-toasty-config`, not `window.LaravelToastyComponent(...)`.

### My PHP helper call works in controllers but not in queue jobs

That is expected. The untargeted helper is session-backed and is intended for the web request lifecycle.

### My toast position or type is wrong

Invalid values are normalized back to the configured defaults.

Valid values are:

- types: `default`, `success`, `info`, `warning`, `danger`, `like`, `bell`
- positions: `top-left`, `top-center`, `top-right`, `bottom-left`, `bottom-center`, `bottom-right`
- layouts: `default`, `expanded`

## Testing

Run the package tests with:

```bash
composer test
```

## Credits

- [DevDojo Pines Toast](https://devdojo.com/pines/docs/toast) for the original toast interaction pattern

## License

MIT
