/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './*.php',
        './inc/**/*.php',
        './templates/**/*.php',
        './parts/**/*.php',
        './assets/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                'deep-freeze': '#0f172a', // Slate 900
                'midnight-navy': '#1e293b', // Slate 800
                'ice-blue': '#38bdf8', // Sky 400
                'fire-crystal': '#f97316', // Orange 500
                'frost-white': '#f1f5f9', // Slate 100
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                display: ['Outfit', 'sans-serif'], // Example for headings
            },
            backgroundImage: {
                'frost-gradient': 'linear-gradient(to bottom right, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.05))',
            },
            backdropBlur: {
                xs: '2px',
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
