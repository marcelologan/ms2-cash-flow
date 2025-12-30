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
            resize: {
                'vertical': 'vertical',
                'horizontal': 'horizontal',
            }
        },
    },

    plugins: [
        forms,
        function({ addUtilities }) {
            addUtilities({
                '.resize-vertical': {
                    resize: 'vertical',
                },
                '.resize-horizontal': {
                    resize: 'horizontal',
                },
                '.resize-none': {
                    resize: 'none',
                },
            })
        }
    ],
};