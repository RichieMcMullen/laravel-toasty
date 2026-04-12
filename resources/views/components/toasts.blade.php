@props([
    'position' => config('toasty.position', 'top-center'),
    'layout' => config('toasty.layout', 'default'),
    'duration' => (int) config('toasty.duration', 4000),
    'paddingBetweenToasts' => (int) config('toasty.padding_between', 15),
    'eventName' => config('toasty.event_name', 'toasty-show'),
    'layoutEventName' => config('toasty.layout_event_name', 'toasty-set-layout'),
    'closeable' => (bool) config('toasty.closeable', true),
    'zIndex' => (int) config('toasty.z_index', 99),
    'theme' => config('toasty.theme', 'toasty'),
    'styles' => config('toasty.styles', []),
])

@php
    $initialToasts = \Atomcoder\Toasty\Support\ToastPayload::normalizeBatch(
        session()->get(config('toasty.session_key', 'toasty.toasts'), [])
    );

    $themes = (array) config('toasty.themes', []);
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
    ];
@endphp

<div {{ $attributes->class('contents') }} x-data="window.ToastyComponent(@js($componentConfig), @js($initialToasts))" x-init="boot()">
    <template x-teleport="body">
        <ul
            x-ref="container"
            x-cloak
            @mouseenter="toastsHovered = true"
            @mouseleave="toastsHovered = false"
            x-on:{{ $eventName }}.window="showFromEvent($event.detail)"
            x-on:{{ $layoutEventName }}.window="setLayout($event.detail.layout ?? layout)"
            class="fixed block w-[calc(100%-1.5rem)] group sm:w-full"
            :style="containerStyle()"
            :class="containerPositionClasses()"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <li
                    :id="toast.id"
                    x-data="{ toastHovered: false }"
                    x-init="initToast(toast)"
                    @mouseover="toastHovered = true"
                    @mouseout="toastHovered = false"
                    class="absolute w-full duration-300 ease-out select-none"
                >
                    <span
                        class="relative flex flex-col items-start w-full overflow-hidden transition-all duration-300 ease-out group"
                        :style="toastCardStyle(toast)"
                        :class="{ 'p-4': !toast.html, 'p-0': toast.html }"
                    >
                        <span
                            class="absolute inset-0 pointer-events-none"
                            :style="toastGlowStyle(toast)"
                        ></span>

                        <template x-if="!toast.html">
                            <div class="relative w-full">
                                <div class="flex items-start gap-3 pr-9">
                                    <span
                                        class="flex items-center justify-center w-11 h-11 shrink-0 rounded-full"
                                        :style="iconBadgeStyle(toast)"
                                    >
                                        <svg x-show="toast.type === 'success'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM16.7744 9.63269C17.1238 9.20501 17.0604 8.57503 16.6327 8.22559C16.2051 7.87615 15.5751 7.93957 15.2256 8.36725L10.6321 13.9892L8.65936 12.2524C8.24484 11.8874 7.61295 11.9276 7.248 12.3421C6.88304 12.7566 6.92322 13.3885 7.33774 13.7535L9.31046 15.4903C10.1612 16.2393 11.4637 16.1324 12.1808 15.2547L16.7744 9.63269Z" fill="currentColor"></path></svg>
                                        <svg x-show="toast.type === 'info'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM12 9C12.5523 9 13 8.55228 13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8C11 8.55228 11.4477 9 12 9ZM13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12V16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16V12Z" fill="currentColor"></path></svg>
                                        <svg x-show="toast.type === 'warning'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.44829 4.46472C10.5836 2.51208 13.4105 2.51168 14.5464 4.46401L21.5988 16.5855C22.7423 18.5509 21.3145 21 19.05 21L4.94967 21C2.68547 21 1.25762 18.5516 2.4004 16.5862L9.44829 4.46472ZM11.9995 8C12.5518 8 12.9995 8.44772 12.9995 9V13C12.9995 13.5523 12.5518 14 11.9995 14C11.4473 14 10.9995 13.5523 10.9995 13V9C10.9995 8.44772 11.4473 8 11.9995 8ZM12.0009 15.99C11.4486 15.9892 11.0003 16.4363 10.9995 16.9886L10.9995 16.9986C10.9987 17.5509 11.4458 17.9992 11.9981 18C12.5504 18.0008 12.9987 17.5537 12.9995 17.0014L12.9995 16.9914C13.0003 16.4391 12.5532 15.9908 12.0009 15.99Z" fill="currentColor"></path></svg>
                                        <svg x-show="toast.type === 'danger'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9996 7C12.5519 7 12.9996 7.44772 12.9996 8V12C12.9996 12.5523 12.5519 13 11.9996 13C11.4474 13 10.9996 12.5523 10.9996 12V8C10.9996 7.44772 11.4474 7 11.9996 7ZM12.001 14.99C11.4488 14.9892 11.0004 15.4363 10.9997 15.9886L10.9996 15.9986C10.9989 16.5509 11.446 16.9992 11.9982 17C12.5505 17.0008 12.9989 16.5537 12.9996 16.0014L12.9996 15.9914C13.0004 15.4391 12.5533 14.9908 12.001 14.99Z" fill="currentColor"></path></svg>
                                        <svg x-show="toast.type === 'like'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.001 20.7279L10.5708 19.4255C5.49046 14.8076 2.13086 11.754 2.13086 8.00439C2.13086 4.95082 4.52643 2.55469 7.58056 2.55469C9.30616 2.55469 10.9622 3.35655 12.001 4.62474C13.0397 3.35655 14.6958 2.55469 16.4214 2.55469C19.4755 2.55469 21.8711 4.95082 21.8711 8.00439C21.8711 11.754 18.5115 14.8076 13.4311 19.4255L12.001 20.7279Z" fill="currentColor"/></svg>
                                        <svg x-show="toast.type === 'bell'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.75C9.51472 3.75 7.5 5.76472 7.5 8.25V10.418C7.5 11.5652 7.10586 12.6776 6.38305 13.5683L5.10273 15.1457C4.69388 15.6495 5.05232 16.4062 5.70098 16.4062H18.299C18.9477 16.4062 19.3061 15.6495 18.8973 15.1457L17.6169 13.5683C16.8941 12.6776 16.5 11.5652 16.5 10.418V8.25C16.5 5.76472 14.4853 3.75 12 3.75Z" fill="currentColor"/><path d="M9.75 18.1875C9.75 19.4301 10.7574 20.4375 12 20.4375C13.2426 20.4375 14.25 19.4301 14.25 18.1875" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                        <svg x-show="toast.type === 'default'" class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8V12M12 16H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p
                                            class="text-[13px] font-semibold leading-none tracking-[-0.01em]"
                                            :style="titleStyle(toast)"
                                            x-text="toast.message"
                                        ></p>

                                        <p
                                            x-show="toast.description"
                                            class="mt-2 text-sm leading-tight"
                                            :style="descriptionStyle(toast)"
                                            x-text="toast.description"
                                        ></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="toast.html">
                            <div class="w-full" x-html="toast.html"></div>
                        </template>

                        <template x-if="toast.closeable">
                            <span
                                @click="burnToast(toast.id)"
                                class="absolute right-0 p-1.5 mr-2.5 duration-100 ease-in-out rounded-full cursor-pointer"
                                :style="closeButtonStyle(toast, toastHovered)"
                                :class="{
                                    'top-1/2 -translate-y-1/2': !toast.description && !toast.html,
                                    'top-0 mt-2.5': toast.description || toast.html,
                                    'opacity-100': toastHovered || layout === 'expanded',
                                    'opacity-0': !toastHovered && layout !== 'expanded'
                                }"
                            >
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </span>
                        </template>
                    </span>
                </li>
            </template>
        </ul>
    </template>
</div>

@once
    <script>
        (() => {
            if (window.ToastyComponent) {
                return;
            }

            window.Toasty = window.Toasty || {
                defaults: {
                    eventName: 'toasty-show',
                    layoutEventName: 'toasty-set-layout',
                    position: 'top-center',
                    duration: 4000,
                    closeable: true,
                    theme: {},
                },
                toast(message, options = {}) {
                    const defaults = window.Toasty.defaults || {};
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

                    window.dispatchEvent(new CustomEvent(options.event ?? defaults.eventName ?? 'toasty-show', {
                        detail: payload,
                    }));
                },
                layout(layout = 'expanded', eventName = null) {
                    const defaults = window.Toasty.defaults || {};

                    window.dispatchEvent(new CustomEvent(eventName ?? defaults.layoutEventName ?? 'toasty-set-layout', {
                        detail: { layout },
                    }));
                },
            };

            window.toast = window.toast || window.Toasty.toast;

            window.ToastyComponent = function (config = {}, initialToasts = []) {
                return {
                    toasts: [],
                    timers: {},
                    toastsHovered: false,
                    expanded: config.layout === 'expanded',
                    layout: config.layout ?? 'default',
                    position: config.position ?? 'top-center',
                    duration: config.duration ?? 4000,
                    paddingBetweenToasts: config.paddingBetweenToasts ?? 15,
                    eventName: config.eventName ?? 'toasty-show',
                    layoutEventName: config.layoutEventName ?? 'toasty-set-layout',
                    closeable: config.closeable ?? true,
                    zIndex: config.zIndex ?? 99,
                    theme: config.theme ?? {},
                    initialToasts,
                    boot() {
                        window.Toasty.defaults = {
                            eventName: this.eventName,
                            layoutEventName: this.layoutEventName,
                            position: this.position,
                            duration: this.duration,
                            closeable: this.closeable,
                            theme: this.theme,
                        };

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
                            id: 'toast-' + Math.random().toString(16).slice(2),
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

                        if (this.position.includes('bottom')) {
                            inner.classList.add('opacity-0', 'translate-y-full');
                        } else {
                            inner.classList.add('opacity-0', '-translate-y-full');
                        }

                        setTimeout(() => {
                            if (this.position.includes('bottom')) {
                                inner.classList.remove('opacity-0', 'translate-y-full');
                            } else {
                                inner.classList.remove('opacity-0', '-translate-y-full');
                            }

                            inner.classList.add('opacity-100', 'translate-y-0');
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

                        inner.classList.remove('translate-y-0', 'opacity-100');
                        inner.classList.add('opacity-0');

                        if (this.position.includes('bottom')) {
                            inner.classList.add('translate-y-full');
                        } else {
                            inner.classList.add('-translate-y-full');
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
                        return {
                            zIndex: this.zIndex,
                            maxWidth: this.theme.max_width ?? '20rem',
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
                    containerPositionClasses() {
                        return {
                            'right-0 top-0 sm:mt-6 sm:mr-6': this.position === 'top-right',
                            'left-0 top-0 sm:mt-6 sm:ml-6': this.position === 'top-left',
                            'left-1/2 -translate-x-1/2 top-0 sm:mt-6': this.position === 'top-center',
                            'right-0 bottom-0 sm:mr-6 sm:mb-6': this.position === 'bottom-right',
                            'left-0 bottom-0 sm:ml-6 sm:mb-6': this.position === 'bottom-left',
                            'left-1/2 -translate-x-1/2 bottom-0 sm:mb-6': this.position === 'bottom-center',
                        };
                    },
                };
            };
        })();
    </script>
@endonce
