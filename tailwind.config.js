const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Alata', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '100': '25rem',
            },
            colors: {
                'spotify': '#1DB954',
            },
        },
    },

    // Enabled plugins
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require("daisyui")
    ],

    // DaisyUI config
    daisyui: {
        themes: [
            {
                mytheme: {
                    "primary": "#1d4ed8",
                    "secondary": "#D926A9",
                    "accent": "#6419E6",
                    ".btn-spotify": {
                        "border-color": "#1DB954",
                        "background-color": "#1DB954",
                        "color": "#FFFFFF",
                    },
                    '.btn-spotify:hover': {
                        'background-color': '#109b41',
                        'border-color': '#109b41',
                    },
                    "neutral": "#191D24",
                    "base-100": "#2A303C",
                    "info": "#3ABFF8",
                    "success": "#36D399",
                    "warning": "#FBBD23",
                    "error": "#F87272",
                },
            },
        ],
    },
};
