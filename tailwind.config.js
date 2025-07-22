import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js', // Añadido para Flowbite
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: '#17223b', // azul marino oscuro
                accent: '#234e70', // azul acento
                gold: '#eab308', // dorado suave
                light: '#f5f6fa', // gris muy claro
                white: '#ffffff',
                gray: {
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
            },
            backgroundImage: {
                'elegant-gradient': 'linear-gradient(135deg, #17223b 0%, #234e70 100%)',
            },
            boxShadow: {
                'elegant': '0 4px 24px 0 rgba(35, 78, 112, 0.10)',
            },
        },
    },

    plugins: [forms, require('flowbite/plugin')], // Añadido Flowbite plugin
};
