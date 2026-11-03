/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Design Tokens FutsalHub Original (Light Mode Compliant)
        action: {
          primary: '#15803D',       // green-700
          'primary-hover': '#166534', // green-800
        },
        surface: {
          page: '#F1F5F9',          // slate-100 (Lebih soft, tidak silau dibanding slate-50)
          card: '#FFFFFF',          // white
        },
        text: {
          default: '#0F172A',       // slate-900
          muted: '#475569',         // slate-600
        },
        border: {
          default: '#E2E8F0',       // slate-200
        },
        // Status Badge Tokens
        status: {
          warning: { bg: '#FEF3C7', text: '#92400E' },
          success: { bg: '#DCFCE7', text: '#166534' },
          danger:  { bg: '#FEE2E2', text: '#991B1B' },
          info:    { bg: '#E0F2FE', text: '#075985' },
          neutral: { bg: '#F1F5F9', text: '#334155' },
        }
      }
    },
  },
  plugins: [],
}