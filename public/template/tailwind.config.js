/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './index.html',
    './components/**/*.html',
    './pages/**/*.html',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#eef8f3',
          100: '#d7f0e3',
          200: '#b2e2ca',
          300: '#7eceaa',
          400: '#41ab75',
          500: '#26915e',
          600: '#1b744a',
          700: '#175d3d',
          800: '#14422b', // Deep Signature Green
          900: '#113825',
          dark: '#0c2619',
        },
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      spacing: {
        '5.5': '1.375rem',
      },
      backdropBlur: {
        xs: '2px',
      },
      boxShadow: {
        xs: '0 1px 2px 0 rgba(18, 38, 25, 0.05)',
        card: '0 4px 20px -2px rgba(18, 38, 25, 0.05)',
        'card-hover': '0 12px 32px -4px rgba(18, 38, 25, 0.09)',
        glass: '0 20px 40px -15px rgba(0, 0, 0, 0.05), inset 0 1px 1px 0 rgba(255, 255, 255, 0.9)',
        floating: '0 20px 50px -10px rgba(20, 66, 43, 0.25)',
      },
    },
  },
  plugins: [],
};
