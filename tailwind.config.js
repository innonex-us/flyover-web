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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Merriweather', 'Georgia', 'serif'],
            },
            colors: {
                brand: {
                    red:   '#C8102E',
                    dark:  '#1a1a1a',
                    light: '#FFF5F5',
                }
            },
        },
    },

    plugins: [forms],
};
