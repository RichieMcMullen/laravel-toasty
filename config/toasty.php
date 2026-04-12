<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toast Event Names
    |--------------------------------------------------------------------------
    |
    | These browser events power the toast stack. "event_name" is what the
    | global window.toast() helper and Livewire trait dispatch. The layout
    | event allows switching between the default stacked layout and the fully
    | expanded layout at runtime.
    |
    */
    'event_name' => 'toasty-show',
    'layout_event_name' => 'toasty-set-layout',

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | Toasts flashed from PHP are stored in the session until the next request
    | where the rendered toast stack consumes and shows them on page load.
    |
    */
    'session_key' => 'toasty.toasts',

    /*
    |--------------------------------------------------------------------------
    | Default Appearance
    |--------------------------------------------------------------------------
    */
    'position' => 'top-center',
    'layout' => 'default',
    'duration' => 4000,
    'padding_between' => 15,
    'closeable' => true,
    'z_index' => 99,

    /*
    |--------------------------------------------------------------------------
    | Visual Theme
    |--------------------------------------------------------------------------
    |
    | Available themes:
    | - pines: light and clean, closest to the original Pines feel
    | - toasty: warm gradients and richer depth to match the package artwork
    | - glass: cool translucent cards with a softer glassmorphism finish
    |
    | Use "styles" to override only the pieces you want to change.
    |
    */
    'theme' => 'pines',

    /*
    |--------------------------------------------------------------------------
    | Theme Presets
    |--------------------------------------------------------------------------
    |
    | These are the bundled theme definitions referenced by the "theme" key.
    | You can override any part of the active theme using the top-level
    | "styles" array below without copying an entire preset.
    |
    */
    'themes' => [
        'pines' => [
            'max_width' => '20rem',
            'base' => [
                'radius' => '0.5rem',
                'border_width' => '1px',
                'backdrop_blur' => '0px',
                'font_family' => 'inherit',
                'background' => '#ffffff',
                'border_color' => '#f3f4f6',
                'shadow' => '0 5px 15px -3px rgba(0, 0, 0, 0.08)',
                'glow' => null,
                'title_color' => '#1f2937',
                'description_color' => '#4b5563',
                'icon_badge_background' => 'rgba(243, 244, 246, 0.95)',
                'icon_badge_shadow' => 'none',
                'icon_color' => '#1f2937',
                'close_color' => '#9ca3af',
                'close_hover_color' => '#6b7280',
                'close_hover_background' => 'rgba(249, 250, 251, 0.95)',
            ],
            'types' => [
                'default' => [
                    'icon_color' => '#1f2937',
                ],
                'success' => [
                    'title_color' => '#14532d',
                    'description_color' => 'rgba(20, 83, 45, 0.76)',
                    'icon_badge_background' => 'rgba(34, 197, 94, 0.14)',
                    'icon_color' => '#22c55e',
                ],
                'info' => [
                    'title_color' => '#1d4ed8',
                    'description_color' => 'rgba(29, 78, 216, 0.76)',
                    'icon_badge_background' => 'rgba(59, 130, 246, 0.14)',
                    'icon_color' => '#3b82f6',
                ],
                'warning' => [
                    'title_color' => '#9a3412',
                    'description_color' => 'rgba(154, 52, 18, 0.76)',
                    'icon_badge_background' => 'rgba(251, 146, 60, 0.16)',
                    'icon_color' => '#fb923c',
                ],
                'danger' => [
                    'title_color' => '#b91c1c',
                    'description_color' => 'rgba(185, 28, 28, 0.76)',
                    'icon_badge_background' => 'rgba(239, 68, 68, 0.14)',
                    'icon_color' => '#ef4444',
                ],
                'like' => [
                    'title_color' => '#9d174d',
                    'description_color' => 'rgba(157, 23, 77, 0.76)',
                    'icon_badge_background' => 'rgba(236, 72, 153, 0.14)',
                    'icon_color' => '#ec4899',
                ],
                'bell' => [
                    'title_color' => '#7c3aed',
                    'description_color' => 'rgba(124, 58, 237, 0.76)',
                    'icon_badge_background' => 'rgba(139, 92, 246, 0.14)',
                    'icon_color' => '#8b5cf6',
                ],
            ],
        ],

        'toasty' => [
            'max_width' => '28rem',
            'base' => [
                'radius' => '1.25rem',
                'border_width' => '1px',
                'backdrop_blur' => '14px',
                'font_family' => 'inherit',
                'background' => 'linear-gradient(180deg, rgba(31, 41, 55, 0.98), rgba(17, 24, 39, 0.98))',
                'border_color' => 'rgba(255, 255, 255, 0.1)',
                'shadow' => '0 24px 55px -24px rgba(15, 23, 42, 0.55)',
                'glow' => 'radial-gradient(circle at top, rgba(251, 191, 36, 0.2), transparent 68%)',
                'title_color' => '#fff7ed',
                'description_color' => 'rgba(255, 247, 237, 0.84)',
                'icon_badge_background' => 'rgba(255, 255, 255, 0.16)',
                'icon_badge_shadow' => 'inset 0 1px 0 rgba(255, 255, 255, 0.18)',
                'icon_color' => '#fff7ed',
                'close_color' => 'rgba(255, 255, 255, 0.72)',
                'close_hover_color' => '#ffffff',
                'close_hover_background' => 'rgba(255, 255, 255, 0.12)',
            ],
            'types' => [
                'default' => [
                    'background' => 'linear-gradient(135deg, rgba(29, 24, 39, 0.98), rgba(17, 24, 39, 0.98))',
                    'border_color' => 'rgba(251, 191, 36, 0.18)',
                    'glow' => 'radial-gradient(circle at top, rgba(251, 191, 36, 0.22), transparent 66%)',
                ],
                'success' => [
                    'background' => 'linear-gradient(135deg, #3f7d20, #1f4d12)',
                    'border_color' => 'rgba(190, 242, 100, 0.38)',
                    'shadow' => '0 28px 55px -24px rgba(20, 83, 45, 0.7)',
                    'glow' => 'radial-gradient(circle at top, rgba(163, 230, 53, 0.26), transparent 66%)',
                    'title_color' => '#f7fee7',
                    'description_color' => 'rgba(247, 254, 231, 0.88)',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.14)',
                    'icon_color' => '#f7fee7',
                    'close_color' => 'rgba(247, 254, 231, 0.75)',
                    'close_hover_color' => '#ffffff',
                    'close_hover_background' => 'rgba(255, 255, 255, 0.12)',
                ],
                'info' => [
                    'background' => 'linear-gradient(135deg, #2563eb, #1d4ed8)',
                    'border_color' => 'rgba(191, 219, 254, 0.34)',
                    'shadow' => '0 28px 55px -24px rgba(29, 78, 216, 0.68)',
                    'glow' => 'radial-gradient(circle at top, rgba(125, 211, 252, 0.22), transparent 66%)',
                    'title_color' => '#eff6ff',
                    'description_color' => 'rgba(239, 246, 255, 0.86)',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.16)',
                    'icon_color' => '#eff6ff',
                    'close_color' => 'rgba(239, 246, 255, 0.74)',
                    'close_hover_color' => '#ffffff',
                    'close_hover_background' => 'rgba(255, 255, 255, 0.12)',
                ],
                'warning' => [
                    'background' => 'linear-gradient(135deg, #fde047, #f59e0b)',
                    'border_color' => 'rgba(120, 53, 15, 0.18)',
                    'shadow' => '0 28px 55px -24px rgba(217, 119, 6, 0.75)',
                    'glow' => 'radial-gradient(circle at top, rgba(254, 240, 138, 0.34), transparent 66%)',
                    'title_color' => '#422006',
                    'description_color' => 'rgba(66, 32, 6, 0.82)',
                    'icon_badge_background' => 'rgba(255, 251, 235, 0.44)',
                    'icon_color' => '#7c2d12',
                    'close_color' => 'rgba(66, 32, 6, 0.62)',
                    'close_hover_color' => '#422006',
                    'close_hover_background' => 'rgba(255, 251, 235, 0.42)',
                ],
                'danger' => [
                    'background' => 'linear-gradient(135deg, #ef4444, #b91c1c)',
                    'border_color' => 'rgba(254, 202, 202, 0.32)',
                    'shadow' => '0 28px 55px -24px rgba(185, 28, 28, 0.76)',
                    'glow' => 'radial-gradient(circle at top, rgba(252, 165, 165, 0.22), transparent 66%)',
                    'title_color' => '#fff1f2',
                    'description_color' => 'rgba(255, 241, 242, 0.88)',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.14)',
                    'icon_color' => '#fff1f2',
                    'close_color' => 'rgba(255, 241, 242, 0.74)',
                    'close_hover_color' => '#ffffff',
                    'close_hover_background' => 'rgba(255, 255, 255, 0.12)',
                ],
                'like' => [
                    'background' => 'linear-gradient(135deg, #ec4899, #9d174d)',
                    'border_color' => 'rgba(251, 207, 232, 0.34)',
                    'shadow' => '0 28px 55px -24px rgba(157, 23, 77, 0.74)',
                    'glow' => 'radial-gradient(circle at top, rgba(249, 168, 212, 0.24), transparent 66%)',
                    'title_color' => '#fdf2f8',
                    'description_color' => 'rgba(253, 242, 248, 0.88)',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.14)',
                    'icon_color' => '#fdf2f8',
                    'close_color' => 'rgba(253, 242, 248, 0.74)',
                    'close_hover_color' => '#ffffff',
                    'close_hover_background' => 'rgba(255, 255, 255, 0.12)',
                ],
                'bell' => [
                    'background' => 'linear-gradient(135deg, #8b5cf6, #5b21b6)',
                    'border_color' => 'rgba(221, 214, 254, 0.32)',
                    'shadow' => '0 28px 55px -24px rgba(91, 33, 182, 0.72)',
                    'glow' => 'radial-gradient(circle at top, rgba(196, 181, 253, 0.22), transparent 66%)',
                    'title_color' => '#f5f3ff',
                    'description_color' => 'rgba(245, 243, 255, 0.88)',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.14)',
                    'icon_color' => '#f5f3ff',
                    'close_color' => 'rgba(245, 243, 255, 0.74)',
                    'close_hover_color' => '#ffffff',
                    'close_hover_background' => 'rgba(255, 255, 255, 0.12)',
                ],
            ],
        ],

        'glass' => [
            'max_width' => '24rem',
            'base' => [
                'radius' => '1rem',
                'border_width' => '1px',
                'backdrop_blur' => '18px',
                'font_family' => 'inherit',
                'background' => 'linear-gradient(180deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.58))',
                'border_color' => 'rgba(255, 255, 255, 0.5)',
                'shadow' => '0 24px 48px -28px rgba(15, 23, 42, 0.35)',
                'glow' => 'radial-gradient(circle at top, rgba(148, 163, 184, 0.18), transparent 68%)',
                'title_color' => '#0f172a',
                'description_color' => '#475569',
                'icon_badge_background' => 'rgba(255, 255, 255, 0.7)',
                'icon_badge_shadow' => 'inset 0 1px 0 rgba(255, 255, 255, 0.75)',
                'icon_color' => '#0f172a',
                'close_color' => 'rgba(15, 23, 42, 0.55)',
                'close_hover_color' => '#0f172a',
                'close_hover_background' => 'rgba(255, 255, 255, 0.5)',
            ],
            'types' => [
                'default' => [
                    'border_color' => 'rgba(148, 163, 184, 0.3)',
                ],
                'success' => [
                    'background' => 'linear-gradient(180deg, rgba(236, 253, 245, 0.88), rgba(209, 250, 229, 0.66))',
                    'border_color' => 'rgba(16, 185, 129, 0.24)',
                    'title_color' => '#065f46',
                    'description_color' => '#047857',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.7)',
                    'icon_color' => '#10b981',
                ],
                'info' => [
                    'background' => 'linear-gradient(180deg, rgba(239, 246, 255, 0.88), rgba(219, 234, 254, 0.66))',
                    'border_color' => 'rgba(59, 130, 246, 0.24)',
                    'title_color' => '#1d4ed8',
                    'description_color' => '#2563eb',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.7)',
                    'icon_color' => '#3b82f6',
                ],
                'warning' => [
                    'background' => 'linear-gradient(180deg, rgba(255, 251, 235, 0.92), rgba(254, 243, 199, 0.72))',
                    'border_color' => 'rgba(245, 158, 11, 0.28)',
                    'title_color' => '#92400e',
                    'description_color' => '#b45309',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.72)',
                    'icon_color' => '#f59e0b',
                ],
                'danger' => [
                    'background' => 'linear-gradient(180deg, rgba(254, 242, 242, 0.9), rgba(254, 226, 226, 0.7))',
                    'border_color' => 'rgba(239, 68, 68, 0.26)',
                    'title_color' => '#b91c1c',
                    'description_color' => '#dc2626',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.72)',
                    'icon_color' => '#ef4444',
                ],
                'like' => [
                    'background' => 'linear-gradient(180deg, rgba(253, 242, 248, 0.92), rgba(252, 231, 243, 0.72))',
                    'border_color' => 'rgba(236, 72, 153, 0.26)',
                    'title_color' => '#9d174d',
                    'description_color' => '#be185d',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.72)',
                    'icon_color' => '#ec4899',
                ],
                'bell' => [
                    'background' => 'linear-gradient(180deg, rgba(245, 243, 255, 0.92), rgba(237, 233, 254, 0.72))',
                    'border_color' => 'rgba(139, 92, 246, 0.26)',
                    'title_color' => '#6d28d9',
                    'description_color' => '#7c3aed',
                    'icon_badge_background' => 'rgba(255, 255, 255, 0.72)',
                    'icon_color' => '#8b5cf6',
                ],
            ],
        ],
    ],

    'styles' => [],
];
