// tailwind.config.js
// const { colors } = require('tailwindcss/defaultTheme');

module.exports = {
    plugins: [
        require('@tailwindcss/custom-forms')
    ],
    theme: {
        extend: {
            colors: {
                primary: '#F56565',
                secondary: '#F7FAFC',
                bgprimary: '#2D3748',
                bgsecondary: '#1A202C'
            }
        }
    },
    purge: {
        enabled: process.env.NODE_ENV === 'production',
        content: [
            'components/**/*.vue',
            'layouts/**/*.vue',
            'pages/**/*.vue',
            'plugin/**/*.js',
            'nuxt.config.js'
        ]
    }
}
