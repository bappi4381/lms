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
            }
        },
    },

    plugins: [forms],
};
