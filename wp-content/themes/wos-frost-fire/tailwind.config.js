/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './*.php',
        './inc/**/*.php',
        './templates/**/*.php',
        './assets/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                'ice-blue': '#e0f7fa',
                'deep-freeze': '#1a237e',
                'fire-crystal': '#ff3d00',
                'midnight': '#0d1117',
            },
            fontFamily: {
                display: ['Iceberg', 'sans-serif'],
                body: ['Roboto', 'sans-serif'],
            },
            backgroundImage: {
                'frost-gradient': 'linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0))',
            },
            backdropBlur: {
                xs: '2px',
            }
        },
    },
    plugins: [],
}
