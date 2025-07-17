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
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
                heading: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Новая красная палитра из вашего дизайна
                'primary': {
                    DEFAULT: '#c1272d', // --primary-color
                    dark: '#9e1c21',    // --primary-dark
                },
                'secondary': '#e74c3c', // --secondary-color
                'accent': {
                    DEFAULT: '#ff6b6b', // --accent-color
                    hover: '#ff5252',   // --accent-hover
                },
                'background-light': '#fafafa', // --background-light
                'surface': '#ffffff',          // --surface-color
                'border-color': '#eeeeee',     // --border-color
                'text-primary': '#333333',     // --text-primary
                'text-secondary': '#555555',   // --text-secondary
                'text-on-primary': '#ffffff',  // --text-on-primary
            },
            boxShadow: {
                'sm': '0 2px 6px rgba(0, 0, 0, 0.05)',       // --shadow-sm
                'md': '0 5px 15px rgba(0, 0, 0, 0.08)',      // --shadow-md
                'lg': '0 10px 25px rgba(0, 0, 0, 0.1)',      // --shadow-lg
                'accent': '0 5px 15px rgba(255, 107, 107, 0.3)', // --shadow-accent
            },
            borderRadius: {
                'md': '8px',  // --border-radius-md
                'lg': '16px', // --border-radius-lg
            },
            spacing: {
                'unit': '8px', // --spacing-unit
            }
        },
    },

    plugins: [forms],
};