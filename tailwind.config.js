/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  safelist: [
    // Post card badge colors
    'bg-green-50', 'text-green-800', 'border-green-200', 'bg-green-100',
    'bg-red-50',   'text-red-800',   'border-red-200',   'text-red-700',
    'bg-blue-50',  'text-blue-800',  'border-blue-200',  'text-blue-700',
    'bg-purple-50','text-purple-800','border-purple-200','text-purple-700',
    'bg-amber-50', 'text-amber-800', 'border-amber-200', 'text-amber-700',
    'bg-teal-50',  'text-teal-700',
    'bg-gray-50',  'text-gray-700',  'border-gray-200',  'bg-gray-100',
    'animate-pulse',
  ],
  theme: {
    extend: {
      colors: {
        // Custom color scheme for Government Job Portal
        'red-primary': '#d32f2f',
        'red-dark': '#b71c1c',
        'navy': '#1565c0',
        'orange-accent': '#ff6f00',
        'light-bg': '#f5f5f5',
        'white-bg': '#ffffff',
        'text-dark': '#212121',
        'text-gray': '#757575',
      },
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        'lg': '0.5rem',
        'xl': '0.75rem',
      },
      boxShadow: {
        'card': '0 2px 8px rgba(0, 0, 0, 0.1)',
        'hover': '0 4px 12px rgba(0, 0, 0, 0.15)',
      },
      transitionDuration: {
        '300': '300ms',
        '500': '500ms',
      },
    },
  },
  plugins: [],
}
