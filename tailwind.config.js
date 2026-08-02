import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Rubik', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                accent: {
                    DEFAULT: '#1D7270',
                    hover: '#14615F',
                    glow: 'rgba(29, 114, 112, 0.35)',
                    dark: '#5BB5AC',
                    'dark-hover': '#4A9E96',
                    'dark-glow': 'rgba(91, 181, 172, 0.35)',
                },
                surface: {
                    canvas: '#F4F0EE',
                    default: '#FFFFFF',
                    muted: '#EBE8E6',
                    container: '#E5E1DF',
                },
                pastel: {
                    lavender: '#F0F4F4',
                    sky: '#E8F4F8',
                    mint: '#E0F0ED',
                    peach: '#FDF0E9',
                    rose: '#FCE8EC',
                    cream: '#FEF9F5',
                },
                pintar: {
                    grey: '#E6E7E8',
                    mustard: '#D69B55',
                    pink: '#E3879A',
                    blue: '#8BC9E3',
                    peach: '#FDF0E9',
                },
                brand: {
                    teal: '#1D7270',
                    'teal-mid': '#1C726F',
                    'teal-deep': '#14615F',
                    'teal-card': '#38796F',
                    'teal-light': '#1D7270',
                    navy: '#0E1E1D',
                    'navy-light': '#1D7270',
                    orange: '#FF7A2E',
                    'orange-bright': '#F37736',
                    'orange-soft': '#E06524',
                    blue: '#1D7270',
                    'blue-light': 'rgba(29, 114, 112, 0.12)',
                    gold: '#F4C56D',
                    cream: '#F4F0EE',
                },
                ostad: {
                    yellow: '#1D6D66',
                    'yellow-hover': '#105151',
                    'yellow-active': '#225E5A',
                    black: '#105151',
                    'black-overlay': '#1D6D66',
                    'black-light': '#1D6D66',
                    'black-muted': '#64748B',
                },
                palette: {
                    dark: '#D1D9E6',
                    light: '#E0E5EC',
                },
                sage: {
                    light: '#E0E5EC',
                    dark: '#D1D9E6',
                },
                neu: {
                    base: '#F4F0EE',
                    'base-dark': '#0F1A19',
                    heading: '#111827',
                    text: '#111827',
                    muted: '#6B7280',
                    'muted-dark': '#94A3B8',
                    dark: '#475569',
                    light: '#FFFFFF',
                    'shadow-dark': '#111827',
                    'shadow-light': '#EEF5F4',
                }
            },
            // Material Design 3 elevation tokens (dp0 - dp5)
            boxShadow: {
                'elevation-0': 'none',
                'elevation-1': '0 1px 2px 0 rgba(16,24,40,0.30), 0 1px 3px 1px rgba(16,24,40,0.15)',
                'elevation-2': '0 1px 2px 0 rgba(16,24,40,0.30), 0 2px 6px 2px rgba(16,24,40,0.15)',
                'elevation-3': '0 1px 3px 0 rgba(16,24,40,0.30), 0 4px 8px 3px rgba(16,24,40,0.15)',
                'elevation-4': '0 2px 3px 0 rgba(16,24,40,0.30), 0 6px 10px 4px rgba(16,24,40,0.15)',
                'elevation-5': '0 4px 4px 0 rgba(16,24,40,0.30), 0 8px 12px 6px rgba(16,24,40,0.15)',
                /* Legacy aliases → MD3 elevation (backward compat during migration) */
                'glass-sm': '0 1px 2px 0 rgba(16,24,40,0.30), 0 1px 3px 1px rgba(16,24,40,0.15)',
                'glass': '0 1px 2px 0 rgba(16,24,40,0.30), 0 2px 6px 2px rgba(16,24,40,0.15)',
                'glass-lg': '0 1px 3px 0 rgba(16,24,40,0.30), 0 4px 8px 3px rgba(16,24,40,0.15)',
                'neu-raised-sm': '0 1px 2px 0 rgba(16,24,40,0.30), 0 1px 3px 1px rgba(16,24,40,0.15)',
                'neu-raised': '0 1px 2px 0 rgba(16,24,40,0.30), 0 2px 6px 2px rgba(16,24,40,0.15)',
                'neu-raised-lg': '0 1px 3px 0 rgba(16,24,40,0.30), 0 4px 8px 3px rgba(16,24,40,0.15)',
                'neu-inset': 'none',
                'neu-inset-sm': 'none',
                'neu-inset-dark': 'none',
                'neu-raised-dark': '0 1px 2px 0 rgba(16,24,40,0.30), 0 2px 6px 2px rgba(16,24,40,0.15)',
                'neu-raised-dark-sm': '0 1px 2px 0 rgba(16,24,40,0.30), 0 1px 3px 1px rgba(16,24,40,0.15)',
                'accent-glow': '0 1px 2px 0 rgba(16,24,40,0.30), 0 1px 3px 1px rgba(16,24,40,0.15)',
                'accent-glow-dark': '0 1px 2px 0 rgba(16,24,40,0.30), 0 1px 3px 1px rgba(16,24,40,0.15)',
                'dark-glass': '0 1px 2px 0 rgba(16,24,40,0.30), 0 2px 6px 2px rgba(16,24,40,0.15)',
            },
            backdropBlur: {
                xs: '2px',
            },
            transitionTimingFunction: {
                'md-standard': 'cubic-bezier(0.2, 0, 0, 1)',
                'md-emphasized': 'cubic-bezier(0.3, 0, 0, 1)',
            },
            borderRadius: {
                'md-xs': '4px',
                'md-sm': '8px',
                'md-md': '12px',
                'md-lg': '16px',
                'md-xl': '28px',
                'md-full': '9999px',
            },
        },
    },

    plugins: [forms],
};
