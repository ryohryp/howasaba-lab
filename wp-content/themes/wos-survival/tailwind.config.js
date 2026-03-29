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
                // New MD3 Tokens from Stitch (Pop Style)
                'primary': '#005da7',
                'on-primary': '#eef3ff',
                'primary-container': '#68abff',
                'on-primary-container': '#002b52',
                'secondary': '#874e00',
                'on-secondary': '#fff0e5',
                'secondary-container': '#ffc791',
                'on-secondary-container': '#6a3c00',
                'tertiary': '#6d5a00',
                'on-tertiary': '#fff2ce',
                'tertiary-container': '#fdd400',
                'on-tertiary-container': '#594a00',
                'error': '#b31b25',
                'on-error': '#ffefee',
                'error-container': '#fb5151',
                'on-error-container': '#570008',
                'background': '#eff7fe',
                'on-background': '#283035',
                'surface': '#eff7fe',
                'on-surface': '#283035',
                'surface-variant': '#d2dee8',
                'on-surface-variant': '#545d62',
                'outline': '#70787e',
                'outline-variant': '#a6aeb5',
                'inverse-surface': '#070f14',
                'inverse-on-surface': '#969ea4',
                'inverse-primary': '#68abff',
                'surface-dim': '#c9d6e0',
                'surface-bright': '#eff7fe',
                'surface-container-lowest': '#ffffff',
                'surface-container-low': '#e9f2fa',
                'surface-container': '#dfeaf2',
                'surface-container-high': '#d9e4ed',
                'surface-container-highest': '#d2dee8',
                
                // Keep Legacy Tokens for fallback if needed during transition
                'deep-freeze': '#0f172a',
                'ice-blue': '#38bdf8',
                'fire-crystal': '#f97316',
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                display: ['"Plus Jakarta Sans"', 'Outfit', 'sans-serif'],
            },
            borderRadius: {
                'DEFAULT': '1rem',
                'lg': '2rem',
                'xl': '3rem',
                'full': '9999px',
            },
            backgroundImage: {
                'pop-gradient': 'linear-gradient(135deg, #eff7fe 0%, #d9e4ed 100%)',
                'glass-gradient': 'linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0.1) 100%)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
