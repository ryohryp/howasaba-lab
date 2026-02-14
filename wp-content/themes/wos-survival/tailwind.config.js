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
                'ice-blue': '#e0f7fa',
                'deep-freeze': '#1a237e',
                'fire-crystal': '#ff3d00',
                'midnight-navy': '#0d1b2a', // Added based on "Midnight Navy" mention
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
