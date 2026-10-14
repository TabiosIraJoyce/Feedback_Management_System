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
                sans: ['Poppins', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
           colors: {
                lavender: '#138A7A',
                'sidebar-purple': '#145F74',
                'card-lavender': '#FFFFFF',
                'light-lavender': '#F4F8F6',

                'soft-pink': '#FDE2E4',
                'soft-yellow': '#FFF4CC',
                'soft-blue': '#DFF3F7',
                'soft-green': '#E1F4E8',

                'purple-button': '#156F6C',
                'purple-hover': '#0F5D5D',

                'primary-text': '#16324F',
                'secondary-text': '#5F7386',

                'card-border': '#D4E3E8',
                'notification-pink': '#D44B7F',
            },
            boxShadow: {
                'neumorph': '0 18px 44px rgba(20,95,116,0.10), 0 4px 12px rgba(22,50,79,0.06)',
                'soft': '0 12px 32px rgba(22,50,79,0.08)',
            },
        },
    },

    plugins: [forms],
};
