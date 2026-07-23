import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
                ostad: {
                    yellow: '#FFCD33',
                    'yellow-hover': '#FFC000',
                    'yellow-active': '#FFAB00',
                    black: '#101828',
                    'black-overlay': '#1d2939',
                    'black-light': '#344054',
                    'black-muted': '#475467',
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
