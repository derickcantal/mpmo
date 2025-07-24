// tailwind.config.js
import forms from '@tailwindcss/forms';

export default {
  darkMode: 'class',
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './src/**/*.{js,ts,jsx,tsx,mdx}',
    './node_modules/flowbite/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        'mpm-primary': '#FF7C7C',
        'mpm-accent':  '#FFFB7D',
        // drop `mpm-secondary` or remove this if you prefer…
      },
      fontFamily: {
        body: [
          'Inter',
          'ui-sans-serif',
          'system-ui',
          '-apple-system',
          'Segoe UI',
          'Roboto',
          'Helvetica Neue',
          'Arial',
          'Noto Sans',
          'sans-serif',
          'Apple Color Emoji',
          'Segoe UI Emoji',
          'Segoe UI Symbol',
          'Noto Color Emoji'
        ],
        // you can omit redefining `sans` if it’s identical to `body`
      },
      spacing: {
        safe: 'env(safe-area-inset-bottom)',
      },
    }
  },
  plugins: [
    require('flowbite/plugin'),
    forms,
    function ({ addUtilities }) {
      addUtilities({
        '.pb-safe': { paddingBottom: 'env(safe-area-inset-bottom)' },
        '.pt-safe': { paddingTop:    'env(safe-area-inset-top)'    },
      })
    }
  ],
};