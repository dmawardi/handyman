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
                primary: {
                    DEFAULT: '#3F3A36',      // Adjusted dark charcoal
                  },
                  secondary: {
                    DEFAULT: '#C67C2D',      // Slightly softened warm brown
                  },
                  accent: {
                    DEFAULT: '#A0A4AA',      // Cool muted grey-blue
                  },
                  background: {
                    DEFAULT: '#F6F3EB',      // Clean light beige
                  },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
