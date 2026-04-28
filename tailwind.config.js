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
            colors: {
                // Map existing red utilities to AamarTrip logo orange.
                red: {
                    50: '#fff7ed',
                    100: '#ffecd4',
                    200: '#ffd6a8',
                    300: '#ffba72',
                    400: '#fb9a3e',
                    500: '#f7941d',
                    600: '#dd7910',
                    700: '#b75e0f',
                    800: '#934a13',
                    900: '#773f13',
                    950: '#411f08',
                },
                // Map existing blue utilities to AamarTrip logo navy.
                blue: {
                    50: '#eef4ff',
                    100: '#dbe8ff',
                    200: '#bfd5ff',
                    300: '#93b9ff',
                    400: '#5f93f7',
                    500: '#3a72ec',
                    600: '#2758d1',
                    700: '#1f46ad',
                    800: '#1e3d8a',
                    900: '#0b274c',
                    950: '#081a33',
                },
                brand: {
                    navy: '#0b274c',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
