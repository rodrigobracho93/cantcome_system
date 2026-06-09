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
                indigo: {
                    50: 'var(--color-primary-50, #eef2ff)',
                    100: 'var(--color-primary-100, #e0e7ff)',
                    200: 'var(--color-primary-200, #c7d2fe)',
                    300: 'var(--color-primary-300, #a5b4fc)',
                    400: 'var(--color-primary-400, #818cf8)',
                    500: 'var(--color-primary-500, #6366f1)',
                    600: 'var(--color-primary-600, #4f46e5)',
                    700: 'var(--color-primary-700, #4338ca)',
                    800: 'var(--color-primary-800, #3730a3)',
                    900: 'var(--color-primary-900, #312e81)',
                    950: 'var(--color-primary-950, #1e1b4b)',
                },
            },
        },
    },

    plugins: [forms],
};
