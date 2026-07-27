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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                accent: {
                    DEFAULT: '#3498DB',
                    hover: '#5DADE2',
                    glow: 'rgba(52, 152, 219, 0.35)',
                    dark: '#FF3B5C',
                    'dark-hover': '#FF5577',
                    'dark-glow': 'rgba(255, 59, 92, 0.55)',
                },
                ostad: {
                    yellow: '#3498DB',
                    'yellow-hover': '#2E86C1',
                    'yellow-active': '#217DBB',
                    black: '#0F3460',
                    'black-overlay': '#16406E',
                    'black-light': '#3498DB',
                    'black-muted': '#636E72',
                },
                brand: {
                    navy: '#0F3460',
                    'navy-light': '#16406E',
                    blue: '#3498DB',
                    'blue-light': '#EAF4FC',
                    gold: '#F5A623',
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
                    base: '#E0E5EC',
                    'base-dark': '#2C2E33',
                    heading: '#2D3436',
                    text: '#2D3436',
                    muted: '#636E72',
                    'muted-dark': '#555861',
                    dark: '#BEBEBE',
                    light: '#FFFFFF',
                    'shadow-dark': '#1E1F23',
                    'shadow-light': '#3A3D43',
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
                'glass-sm': '6px 6px 14px #d1d9e6, -6px -6px 14px #ffffff',
                'glass': '10px 10px 20px #bebebe, -10px -10px 20px #ffffff',
                'glass-lg': '14px 14px 28px #b8c0cc, -14px -14px 28px #ffffff',
                'neu-raised-sm': '6px 6px 14px #d1d9e6, -6px -6px 14px #ffffff',
                'neu-raised': '10px 10px 20px #bebebe, -10px -10px 20px #ffffff',
                'neu-raised-lg': '14px 14px 28px #b8c0cc, -14px -14px 28px #ffffff',
                'neu-inset': 'inset 8px 8px 16px #bebebe, inset -8px -8px 16px #ffffff',
                'neu-inset-sm': 'inset 4px 4px 8px #d1d9e6, inset -4px -4px 8px #ffffff',
                'neu-inset-dark': 'inset 5px 5px 10px #1e1f23, inset -5px -5px 10px #3a3d43',
                'neu-raised-dark': '10px 10px 20px #1e1f23, -10px -10px 20px #3a3d43',
                'neu-raised-dark-sm': '6px 6px 14px #1e1f23, -6px -6px 14px #3a3d43',
                'accent-glow': '0 4px 16px rgba(52,152,219,0.32)',
                'accent-glow-dark': '0 0 8px rgba(255,59,92,0.65), 0 0 16px rgba(255,59,92,0.35)',
                'dark-glass': '10px 10px 20px #1e1f23, -10px -10px 20px #3a3d43',
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
