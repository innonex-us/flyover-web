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
                sans: ['Geist', 'Inter Tight', ...defaultTheme.fontFamily.sans],
                serif: ['Instrument Serif', 'Cormorant Garamond', 'Georgia', 'serif'],
                mono: ['JetBrains Mono', 'IBM Plex Mono', 'ui-monospace', 'monospace'],
            },
            colors: {
                brand: {
                    red:      '#C8102E',
                    'red-deep': '#9E0B23',
                    'red-ink': '#5C0814',
                    cream:    '#FAF6EE',
                    'cream-deep': '#F1ECDF',
                    paper:    '#FFFFFF',
                    ink:      '#18130E',
                    ink2:     '#3A332B',
                    mute:     '#7A7166',
                    rule:     '#E4DCC9',
                    'rule-soft': '#EFE9DA',
                    emerald:  '#1F4A3D',
                },
            },
            screens: {
                'xs': '480px',
            },
        },
    },

    plugins: [forms],
};
