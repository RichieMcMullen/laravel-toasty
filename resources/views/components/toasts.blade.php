@props([
    'position' => \Atomcoder\Toasty\Support\PackageConfig::get('position', 'top-center'),
    'layout' => \Atomcoder\Toasty\Support\PackageConfig::get('layout', 'default'),
    'duration' => (int) \Atomcoder\Toasty\Support\PackageConfig::get('duration', 4000),
    'paddingBetweenToasts' => (int) \Atomcoder\Toasty\Support\PackageConfig::get('padding_between', 15),
    'eventName' => \Atomcoder\Toasty\Support\PackageConfig::get('event_name', 'laravel-toasty:notify'),
    'layoutEventName' => \Atomcoder\Toasty\Support\PackageConfig::get('layout_event_name', 'laravel-toasty:layout'),
    'closeable' => (bool) \Atomcoder\Toasty\Support\PackageConfig::get('closeable', true),
    'zIndex' => (int) \Atomcoder\Toasty\Support\PackageConfig::get('z_index', 99),
    'theme' => \Atomcoder\Toasty\Support\PackageConfig::get('theme', 'pines'),
    'styles' => \Atomcoder\Toasty\Support\PackageConfig::get('styles', []),
    'legacyAliases' => (bool) \Atomcoder\Toasty\Support\PackageConfig::get('legacy_aliases', false),
])

@php
    $initialToasts = \Atomcoder\Toasty\Support\ToastPayload::normalizeBatch(
        session()->get(\Atomcoder\Toasty\Support\PackageConfig::get('session_key', 'laravel_toasty.toasts'), [])
    );

    $themes = (array) \Atomcoder\Toasty\Support\PackageConfig::get('themes', []);
    $fallbackTheme = (array) ($themes['pines'] ?? []);
    $selectedTheme = (array) ($themes[$theme] ?? $fallbackTheme);
    $resolvedTheme = array_replace_recursive($fallbackTheme, $selectedTheme, (array) $styles);

    $componentConfig = [
        'position' => $position,
        'layout' => $layout,
        'duration' => max(0, (int) $duration),
        'paddingBetweenToasts' => max(0, (int) $paddingBetweenToasts),
        'eventName' => $eventName,
        'layoutEventName' => $layoutEventName,
        'closeable' => (bool) $closeable,
        'zIndex' => (int) $zIndex,
        'theme' => $resolvedTheme,
        'legacyAliases' => (bool) $legacyAliases,
    ];
@endphp

<div {{ $attributes->class('lty-root') }} x-data="window.LaravelToastyComponent(@js($componentConfig), @js($initialToasts))" x-init="boot()">
    <template x-teleport="body">
        <ul
            x-ref="container"
            x-cloak
            @mouseenter="toastsHovered = true"
            @mouseleave="toastsHovered = false"
            x-on:{{ $eventName }}.window="showFromEvent($event.detail)"
            x-on:{{ $layoutEventName }}.window="setLayout($event.detail.layout ?? layout)"
            class="lty-stack"
            :style="containerStyle()"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <li
                    :id="toast.id"
                    x-data="{ toastHovered: false }"
                    x-init="initToast(toast)"
                    @mouseover="toastHovered = true"
                    @mouseout="toastHovered = false"
                    :style="toastItemStyle()"
                >
                    <span
                        class="lty-card"
                        :style="toastCardStyle(toast)"
                        :class="{ 'lty-card--padded': !toast.html, 'lty-card--flush': toast.html }"
                    >
                        <span class="lty-glow" :style="toastGlowStyle(toast)"></span>

                        <template x-if="!toast.html">
                            <div class="lty-body">
                                <div
                                    class="lty-row"
                                    :class="toast.description ? 'lty-row--start' : 'lty-row--center'"
                                >
                                    <span class="lty-icon-badge" :style="iconBadgeStyle(toast)">
                                        <template x-if="toast.type === 'success'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM16.7744 9.63269C17.1238 9.20501 17.0604 8.57503 16.6327 8.22559C16.2051 7.87615 15.5751 7.93957 15.2256 8.36725L10.6321 13.9892L8.65936 12.2524C8.24484 11.8874 7.61295 11.9276 7.248 12.3421C6.88304 12.7566 6.92322 13.3885 7.33774 13.7535L9.31046 15.4903C10.1612 16.2393 11.4637 16.1324 12.1808 15.2547L16.7744 9.63269Z" fill="currentColor"></path></svg>
                                        </template>
                                        <template x-if="toast.type === 'info'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM12 9C12.5523 9 13 8.55228 13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9ZM13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12V16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16V12Z" fill="currentColor"></path></svg>
                                        </template>
                                        <template x-if="toast.type === 'warning'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.44829 4.46472C10.5836 2.51208 13.4105 2.51168 14.5464 4.46401L21.5988 16.5855C22.7423 18.5509 21.3145 21 19.05 21L4.94967 21C2.68547 21 1.25762 18.5516 2.4004 16.5862L9.44829 4.46472ZM11.9995 8C12.5518 8 12.9995 8.44772 12.9995 9V13C12.9995 13.5523 12.5518 14 11.9995 14C11.4473 14 10.9995 13.5523 10.9995 13V9C10.9995 8.44772 11.4473 8 11.9995 8ZM12.0009 15.99C11.4486 15.9892 11.0003 16.4363 10.9995 16.9886L10.9995 16.9986C10.9987 17.5509 11.4458 17.9992 11.9981 18C12.5504 18.0008 12.9987 17.5537 12.9995 17.0014L12.9995 16.9914C13.0003 16.4391 12.5532 15.9908 12.0009 15.99Z" fill="currentColor"></path></svg>
                                        </template>
                                        <template x-if="toast.type === 'danger'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9996 7C12.5519 7 12.9996 7.44772 12.9996 8V12C12.9996 12.5523 12.5519 13 11.9996 13C11.4474 13 10.9996 12.5523 10.9996 12V8C10.9996 7.44772 11.4474 7 11.9996 7ZM12.001 14.99C11.4488 14.9892 11.0004 15.4363 10.9997 15.9886L10.9996 15.9986C10.9989 16.5509 11.446 16.9992 11.9982 17C12.5505 17.0008 12.9989 16.5537 12.9996 16.0014L12.9996 15.9914C13.0004 15.4391 12.5533 14.9908 12.001 14.99Z" fill="currentColor"></path></svg>
                                        </template>
                                        <template x-if="toast.type === 'like'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8634 3.75C10.3246 3.75 9.8711 4.15072 9.80494 4.6855L9.40772 7.89633C9.30682 8.71201 9.02084 9.49391 8.57318 10.1827L7.5514 11.7547C7.25084 12.2172 7.08997 12.7563 7.08997 13.3079V18.2471C7.08997 19.1455 7.81836 19.8739 8.71677 19.8739H15.5407C16.9199 19.8739 18.1045 18.8962 18.3656 17.542L19.2012 13.2104C19.5064 11.6285 18.2937 10.1544 16.6827 10.1544H13.8731L14.3273 6.31154C14.4869 4.96063 13.4314 3.75 12.0711 3.75H10.8634ZM4.81567 11.8723C3.81387 11.8723 3.00183 12.6844 3.00183 13.6862V18.0601C3.00183 19.0619 3.81387 19.8739 4.81567 19.8739H5.96298V11.8723H4.81567Z" fill="currentColor"/></svg>
                                        </template>
                                        <template x-if="toast.type === 'bell'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.75C9.44568 2.75 7.375 4.82068 7.375 7.375V9.34543C7.375 10.3892 7.01671 11.4013 6.36046 12.2128L4.93014 13.9814C4.36916 14.6751 4.86284 15.7188 5.75501 15.7188H18.245C19.1372 15.7188 19.6308 14.6751 19.0699 13.9814L17.6395 12.2128C16.9833 11.4013 16.625 10.3892 16.625 9.34543V7.375C16.625 4.82068 14.5543 2.75 12 2.75Z" fill="currentColor"/><path d="M9.25 17.1562C9.25 18.675 10.4812 19.9062 12 19.9062C13.5188 19.9062 14.75 18.675 14.75 17.1562H9.25Z" fill="currentColor"/></svg>
                                        </template>
                                        <template x-if="toast.type === 'default'">
                                            <svg class="lty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8V12M12 16H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </template>
                                    </span>

                                    <div class="lty-copy">
                                        <p class="lty-title" :style="titleStyle(toast)" x-text="toast.message"></p>

                                        <template x-if="toast.description">
                                            <p class="lty-description" :style="descriptionStyle(toast)" x-text="toast.description"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="toast.html">
                            <div class="lty-html" x-html="toast.html"></div>
                        </template>

                        <template x-if="toast.closeable">
                            <span
                                @click="burnToast(toast.id)"
                                class="lty-close"
                                :style="closeButtonStyle(toast, toastHovered)"
                                :class="{
                                    'lty-close--centered': !toast.description && !toast.html,
                                    'lty-close--top': toast.description || toast.html,
                                    'lty-close--visible': toastHovered || layout === 'expanded',
                                    'lty-close--hidden': !toastHovered && layout !== 'expanded'
                                }"
                            >
                                <svg class="lty-close-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </span>
                        </template>
                    </span>
                </li>
            </template>
        </ul>
    </template>
</div>

@once
    <style>
        .lty-root {
            display: contents;
        }

        .lty-stack,
        .lty-stack * {
            box-sizing: border-box;
        }

        .lty-stack[x-cloak] {
            display: none !important;
        }

        .lty-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            overflow: hidden;
            pointer-events: auto;
            transition:
                transform 300ms ease,
                opacity 300ms ease,
                box-shadow 300ms ease,
                background 300ms ease,
                border-color 300ms ease;
        }

        .lty-card--padded {
            padding: 1rem;
        }

        .lty-card--flush {
            padding: 0;
        }

        .lty-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .lty-body,
        .lty-html {
            position: relative;
            width: 100%;
        }

        .lty-row {
            display: flex;
            gap: 0.75rem;
            padding-right: 2.25rem;
        }

        .lty-row--start {
            align-items: flex-start;
        }

        .lty-row--center {
            align-items: center;
        }

        .lty-icon-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.75rem;
            height: 2.75rem;
            flex-shrink: 0;
            border-radius: 9999px;
        }

        .lty-icon {
            display: block;
            width: 1.5rem;
            height: 1.5rem;
        }

        .lty-copy {
            min-width: 0;
            flex: 1 1 auto;
        }

        .lty-title {
            margin: 0;
            font-size: 13px;
            line-height: 1;
            letter-spacing: -0.01em;
            font-weight: 600;
        }

        .lty-description {
            margin: 0.5rem 0 0;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .lty-close {
            position: absolute;
            right: 0;
            margin-right: 0.625rem;
            padding: 0.375rem;
            border-radius: 9999px;
            cursor: pointer;
            transition:
                opacity 150ms ease,
                background 150ms ease,
                color 150ms ease,
                transform 150ms ease;
        }

        .lty-close--centered {
            top: 50%;
            transform: translateY(-50%);
        }

        .lty-close--top {
            top: 0;
            margin-top: 0.625rem;
        }

        .lty-close--visible {
            opacity: 1;
        }

        .lty-close--hidden {
            opacity: 0;
        }

        .lty-close-icon {
            display: block;
            width: 0.75rem;
            height: 0.75rem;
        }

        .lty-motion-hidden-top {
            opacity: 0;
            transform: translateY(-100%);
        }

        .lty-motion-hidden-bottom {
            opacity: 0;
            transform: translateY(100%);
        }

        .lty-motion-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        (() => {
            if (window.LaravelToastyComponent) {
                return;
            }

            window.LaravelToasty = window.LaravelToasty || {
                defaults: {
                    eventName: 'laravel-toasty:notify',
                    layoutEventName: 'laravel-toasty:layout',
                    position: 'top-center',
                    duration: 4000,
                    closeable: true,
                    theme: {},
                    legacyAliases: false,
                },
                notify(message, options = {}) {
                    const defaults = window.LaravelToasty.defaults || {};
                    const payload = {
                        message: message ?? '',
                        description: options.description ?? null,
                        type: options.type ?? 'default',
                        position: options.position ?? defaults.position ?? 'top-center',
                        html: options.html ?? null,
                        duration: options.duration ?? defaults.duration ?? null,
                        closeable: options.closeable ?? defaults.closeable ?? null,
                        layout: options.layout ?? null,
                    };

                    window.dispatchEvent(new CustomEvent(options.event ?? defaults.eventName ?? 'laravel-toasty:notify', {
                        detail: payload,
                    }));
                },
                toast(message, options = {}) {
                    window.LaravelToasty.notify(message, options);
                },
                layout(layout = 'expanded', eventName = null) {
                    const defaults = window.LaravelToasty.defaults || {};

                    window.dispatchEvent(new CustomEvent(eventName ?? defaults.layoutEventName ?? 'laravel-toasty:layout', {
                        detail: { layout },
                    }));
                },
                setLayout(layout = 'expanded', eventName = null) {
                    window.LaravelToasty.layout(layout, eventName);
                },
            };

            window.laravelToasty = window.LaravelToasty;

            window.LaravelToastyComponent = function (config = {}, initialToasts = []) {
                const mountId = 'laravel-toasty-mount-' + Math.random().toString(16).slice(2);
                const hasActiveMount = Boolean(window.LaravelToasty.activeMountId);

                if (! hasActiveMount) {
                    window.LaravelToasty.activeMountId = mountId;
                }

                return {
                    mountId,
                    disabled: hasActiveMount,
                    toasts: [],
                    timers: {},
                    toastsHovered: false,
                    expanded: config.layout === 'expanded',
                    layout: config.layout ?? 'default',
                    position: config.position ?? 'top-center',
                    duration: config.duration ?? 4000,
                    paddingBetweenToasts: config.paddingBetweenToasts ?? 15,
                    eventName: config.eventName ?? 'laravel-toasty:notify',
                    layoutEventName: config.layoutEventName ?? 'laravel-toasty:layout',
                    closeable: config.closeable ?? true,
                    zIndex: config.zIndex ?? 99,
                    theme: config.theme ?? {},
                    legacyAliases: Boolean(config.legacyAliases ?? false),
                    initialToasts,
                    boot() {
                        if (this.disabled) {
                            console.warn('[Laravel Toasty] Duplicate toast stack detected. Render <x-laravel-toasty::toasts /> only once per page.');
                            return;
                        }

                        window.LaravelToasty.defaults = {
                            eventName: this.eventName,
                            layoutEventName: this.layoutEventName,
                            position: this.position,
                            duration: this.duration,
                            closeable: this.closeable,
                            theme: this.theme,
                            legacyAliases: this.legacyAliases,
                        };

                        window.laravelToasty = window.LaravelToasty;

                        @if ($legacyAliases)
                            if (this.legacyAliases) {
                                window.Toasty = window.LaravelToasty;
                                window.toast = window.toast || ((message, options = {}) => window.LaravelToasty.notify(message, options));
                            }
                        @endif

                        this.initialToasts.forEach((toast) => this.enqueueToast(toast));

                        this.$watch('toastsHovered', (value) => {
                            if (this.layout !== 'default') {
                                return;
                            }

                            if (this.position.includes('bottom')) {
                                this.resetBottom();
                            } else {
                                this.resetTop();
                            }

                            this.expanded = value;
                            this.stackToasts();

                            if (! value) {
                                setTimeout(() => this.stackToasts(), 10);
                            }
                        });
                    },
                    showFromEvent(detail = {}) {
                        if (this.disabled) {
                            return;
                        }

                        if (detail.layout) {
                            this.setLayout(detail.layout);
                        }

                        if (detail.position) {
                            this.position = detail.position;
                        }

                        this.enqueueToast(detail);
                    },
                    setLayout(layout = 'default') {
                        this.layout = layout === 'expanded' ? 'expanded' : 'default';
                        this.expanded = this.layout === 'expanded';
                        this.stackToasts();
                    },
                    enqueueToast(detail = {}) {
                        const toast = {
                            id: 'laravel-toasty-toast-' + Math.random().toString(16).slice(2),
                            message: detail.message ?? '',
                            description: detail.description ?? null,
                            type: ['default', 'success', 'info', 'warning', 'danger', 'like', 'bell'].includes(detail.type) ? detail.type : 'default',
                            position: detail.position ?? this.position,
                            html: detail.html ?? null,
                            duration: Number.isFinite(Number(detail.duration)) ? Math.max(0, Number(detail.duration)) : this.duration,
                            closeable: typeof detail.closeable === 'boolean' ? detail.closeable : this.closeable,
                        };

                        this.position = toast.position;
                        this.toasts.unshift(toast);

                        this.$nextTick(() => this.stackToasts());
                    },
                    initToast(toast) {
                        const element = this.getToastElement(toast.id);

                        if (! element) {
                            return;
                        }

                        const inner = element.firstElementChild;

                        if (! inner) {
                            return;
                        }

                        if (this.position.includes('bottom')) {
                            inner.classList.add('lty-motion-hidden-bottom');
                        } else {
                            inner.classList.add('lty-motion-hidden-top');
                        }

                        setTimeout(() => {
                            inner.classList.remove('lty-motion-hidden-top', 'lty-motion-hidden-bottom');
                            inner.classList.add('lty-motion-visible');
                            this.stackToasts();
                        }, 50);

                        this.scheduleBurn(toast.id, toast.duration);
                    },
                    scheduleBurn(id, duration) {
                        if (duration <= 0) {
                            return;
                        }

                        this.clearTimer(id);

                        this.timers[id] = window.setTimeout(() => {
                            this.burnToast(id);
                        }, duration);
                    },
                    clearTimer(id) {
                        if (! this.timers[id]) {
                            return;
                        }

                        window.clearTimeout(this.timers[id]);
                        delete this.timers[id];
                    },
                    burnToast(id) {
                        const toast = this.getToastWithId(id);
                        const toastElement = this.getToastElement(id);

                        if (! toast || ! toastElement) {
                            this.deleteToastWithId(id);
                            return;
                        }

                        this.clearTimer(id);

                        const inner = toastElement.firstElementChild;

                        if (! inner) {
                            this.deleteToastWithId(id);
                            return;
                        }

                        inner.classList.remove('lty-motion-visible');

                        if (this.position.includes('bottom')) {
                            inner.classList.add('lty-motion-hidden-bottom');
                        } else {
                            inner.classList.add('lty-motion-hidden-top');
                        }

                        setTimeout(() => {
                            this.deleteToastWithId(id);
                            this.stackToasts();
                        }, 300);
                    },
                    deleteToastWithId(id) {
                        this.clearTimer(id);

                        const index = this.toasts.findIndex((toast) => toast.id === id);

                        if (index !== -1) {
                            this.toasts.splice(index, 1);
                        }

                        this.calculateHeightOfToastsContainer();
                    },
                    containerElement() {
                        return this.$refs.container ?? null;
                    },
                    getToastWithId(id) {
                        return this.toasts.find((toast) => toast.id === id);
                    },
                    getToastElement(id) {
                        return document.getElementById(id);
                    },
                    stackToasts() {
                        this.positionToasts();
                        this.calculateHeightOfToastsContainer();

                        setTimeout(() => this.calculateHeightOfToastsContainer(), 300);
                    },
                    positionToasts() {
                        if (this.toasts.length === 0) {
                            return;
                        }

                        const orderedToasts = this.toasts
                            .map((toast) => this.getToastElement(toast.id))
                            .filter(Boolean);

                        orderedToasts.forEach((toastElement, index) => {
                            const offset = this.expanded
                                ? this.calculateExpandedOffset(orderedToasts, index)
                                : index * 16;

                            toastElement.style.zIndex = String(100 - index);
                            toastElement.style.scale = this.expanded
                                ? '100%'
                                : `${Math.max(82, 100 - (index * 6))}%`;

                            if (this.position.includes('bottom')) {
                                toastElement.style.top = 'auto';
                                toastElement.style.bottom = this.expanded ? `${offset}px` : '0px';
                                toastElement.style.transform = this.expanded
                                    ? 'translateY(0px)'
                                    : `translateY(-${index * 16}px)`;
                            } else {
                                toastElement.style.bottom = 'auto';
                                toastElement.style.top = this.expanded ? `${offset}px` : '0px';
                                toastElement.style.transform = this.expanded
                                    ? 'translateY(0px)'
                                    : `translateY(${index * 16}px)`;

                                if (! this.expanded && index > 0) {
                                    this.alignBottom(orderedToasts[0], toastElement);
                                }
                            }
                        });
                    },
                    calculateExpandedOffset(orderedToasts, index) {
                        if (index === 0) {
                            return 0;
                        }

                        let offset = 0;

                        for (let i = 0; i < index; i += 1) {
                            offset += orderedToasts[i].getBoundingClientRect().height + this.paddingBetweenToasts;
                        }

                        return offset;
                    },
                    alignBottom(element1, element2) {
                        if (! element1 || ! element2) {
                            return;
                        }

                        const top = element1.offsetTop + (element1.offsetHeight - element2.offsetHeight);
                        element2.style.top = `${top}px`;
                    },
                    resetBottom() {
                        this.toasts.forEach((toast) => {
                            const element = this.getToastElement(toast.id);

                            if (element) {
                                element.style.bottom = '0px';
                            }
                        });
                    },
                    resetTop() {
                        this.toasts.forEach((toast) => {
                            const element = this.getToastElement(toast.id);

                            if (element) {
                                element.style.top = '0px';
                            }
                        });
                    },
                    calculateHeightOfToastsContainer() {
                        const container = this.containerElement();

                        if (! container) {
                            return;
                        }

                        if (this.toasts.length === 0) {
                            container.style.height = '0px';
                            return;
                        }

                        const firstToast = this.getToastElement(this.toasts[0].id);
                        const lastToast = this.getToastElement(this.toasts[this.toasts.length - 1].id);

                        if (! firstToast || ! lastToast) {
                            return;
                        }

                        const firstRect = firstToast.getBoundingClientRect();
                        const lastRect = lastToast.getBoundingClientRect();

                        if (this.toastsHovered || this.expanded) {
                            if (this.position.includes('bottom')) {
                                container.style.height = `${(firstRect.top + firstRect.height) - lastRect.top}px`;
                            } else {
                                container.style.height = `${(lastRect.top + lastRect.height) - firstRect.top}px`;
                            }

                            return;
                        }

                        container.style.height = `${firstRect.height}px`;
                    },
                    containerStyle() {
                        const maxWidth = this.theme.max_width ?? '20rem';
                        const safeTop = 'calc(1rem + env(safe-area-inset-top, 0px))';
                        const safeBottom = 'calc(1rem + env(safe-area-inset-bottom, 0px))';
                        const safeLeft = 'calc(1rem + env(safe-area-inset-left, 0px))';
                        const safeRight = 'calc(1rem + env(safe-area-inset-right, 0px))';
                        const style = {
                            position: 'fixed',
                            display: 'block',
                            listStyle: 'none',
                            margin: '0',
                            padding: '0',
                            width: `min(calc(100vw - 2rem), ${maxWidth})`,
                            maxWidth,
                            pointerEvents: 'none',
                            zIndex: this.zIndex,
                        };

                        if (this.position === 'top-right') {
                            style.top = safeTop;
                            style.right = safeRight;
                        } else if (this.position === 'top-left') {
                            style.top = safeTop;
                            style.left = safeLeft;
                        } else if (this.position === 'bottom-right') {
                            style.bottom = safeBottom;
                            style.right = safeRight;
                        } else if (this.position === 'bottom-left') {
                            style.bottom = safeBottom;
                            style.left = safeLeft;
                        } else if (this.position === 'bottom-center') {
                            style.bottom = safeBottom;
                            style.left = '50%';
                            style.transform = 'translateX(-50%)';
                        } else {
                            style.top = safeTop;
                            style.left = '50%';
                            style.transform = 'translateX(-50%)';
                        }

                        return {
                            ...style,
                        };
                    },
                    toastItemStyle() {
                        return {
                            position: 'absolute',
                            width: '100%',
                            userSelect: 'none',
                            transition: 'all 300ms ease',
                        };
                    },
                    styleProfile(toast) {
                        const base = this.theme.base ?? {};
                        const typeStyles = (this.theme.types ?? {})[toast.type] ?? {};

                        return {
                            ...base,
                            ...typeStyles,
                        };
                    },
                    toastCardStyle(toast) {
                        const profile = this.styleProfile(toast);
                        const blur = profile.backdrop_blur ?? '0px';

                        return {
                            background: profile.background ?? '#ffffff',
                            border: `${profile.border_width ?? '1px'} solid ${profile.border_color ?? 'transparent'}`,
                            borderRadius: profile.radius ?? '0.75rem',
                            boxShadow: profile.shadow ?? '0 5px 15px -3px rgba(0, 0, 0, 0.08)',
                            backdropFilter: `blur(${blur})`,
                            WebkitBackdropFilter: `blur(${blur})`,
                            fontFamily: profile.font_family ?? 'inherit',
                        };
                    },
                    toastGlowStyle(toast) {
                        const profile = this.styleProfile(toast);

                        if (! profile.glow) {
                            return { display: 'none' };
                        }

                        return {
                            background: profile.glow,
                            opacity: '1',
                        };
                    },
                    iconBadgeStyle(toast) {
                        const profile = this.styleProfile(toast);

                        return {
                            background: profile.icon_badge_background ?? 'rgba(243, 244, 246, 0.95)',
                            color: profile.icon_color ?? profile.title_color ?? '#1f2937',
                            boxShadow: profile.icon_badge_shadow ?? 'none',
                        };
                    },
                    titleStyle(toast) {
                        const profile = this.styleProfile(toast);

                        return {
                            color: profile.title_color ?? '#1f2937',
                        };
                    },
                    descriptionStyle(toast) {
                        const profile = this.styleProfile(toast);

                        return {
                            color: profile.description_color ?? 'rgba(31, 41, 55, 0.72)',
                        };
                    },
                    closeButtonStyle(toast, hovered = false) {
                        const profile = this.styleProfile(toast);

                        return {
                            color: hovered
                                ? (profile.close_hover_color ?? profile.close_color ?? '#6b7280')
                                : (profile.close_color ?? '#9ca3af'),
                            background: hovered
                                ? (profile.close_hover_background ?? 'transparent')
                                : 'transparent',
                        };
                    },
                };
            };
        })();
    </script>
@endonce
