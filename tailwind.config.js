import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // darkMode: "class", // Убедись, что это закомментировано или удалено

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Настраиваем шрифты
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans], // Основной шрифт
                heading: ['Figtree', ...defaultTheme.fontFamily.sans], // Для заголовков
            },
            colors: {
                // Новая единая цветовая палитра
                'primary-main': {
                    DEFAULT: '#2d3748', // --primary-color (Dark Slate Gray)
                    light: '#4a5568',   // --primary-color-light
                    dark: '#1a202c',    // --primary-color-dark
                },
                'accent-main': {
                    DEFAULT: '#3182ce', // --accent-color (Professional Blue)
                    hover: '#2b6cb0',   // --accent-color-hover
                },
                'background-light': '#f7fafc', // --background-light
                'surface': '#ffffff',          // --surface-color
                'border-light': '#e2e8f0',     // --border-color
                'text-primary': '#2d3748',     // --text-primary
                'text-secondary': '#718096',   // --text-secondary
                'text-on-primary': '#ffffff',  // --text-on-primary

                // Дополнительные цвета из старых переменных, если они все еще нужны
                // (Если эти цвета не используются в Tailwind-классах, их можно удалить)
                'red-custom': '#ef4444',
                'green-custom': '#00c650',
            },
            // Настраиваем тени, чтобы они соответствовали твоим --shadow-sm/md/lg
            boxShadow: {
                'sm-custom': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'md-custom': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'lg-custom': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'accent-custom': '0 5px 15px rgba(49, 130, 206, 0.3)', // Соответствует --shadow-accent
            },
            // Настраиваем радиусы
            borderRadius: {
                'sm-custom': '4px',
                'md-custom': '8px',
                'lg-custom': '16px',
            },
            // Настраиваем отступы, чтобы они соответствовали spacing-unit
            spacing: {
                'unit': '8px',
                'unit-1.5': '12px', // 1.5 * 8px
                'unit-2': '16px',   // 2 * 8px
                'unit-3': '24px',   // 3 * 8px
                'unit-4': '32px',   // 4 * 8px
                'unit-8': '64px',   // 8 * 8px
            }
        },
    },

    plugins: [forms],
};
