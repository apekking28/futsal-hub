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
        // Design Tokens FutsalHub - Dark Mode (Adem & Gak Bikin Sakit Mata)
        action: {
          primary: '#10B981',       // emerald-500 (Hijau menyala di bg gelap)
          'primary-hover': '#059669', // emerald-600
        },
        surface: {
          page: '#0F172A',          // slate-900 (Background utama sangat gelap)
          card: '#1E293B',          // slate-800 (Kartu & container gelap soft)
        },
        text: {
          default: '#F8FAFC',       // slate-50 (Teks utama terang soft)
          muted: '#94A3B8',         // slate-400 (Teks abu-abu sedang)
        },
        border: {
          default: '#334155',       // slate-700 (Garis batas tegas tapi teratur)
        },
        // Status Badge Tokens (Disesuaikan untuk Mode Gelap)
        status: {
          warning: { bg: '#451A03', text: '#FCD34D' }, // amber dark
          success: { bg: '#064E3B', text: '#6EE7B7' }, // green dark
          danger:  { bg: '#450A0A', text: '#FCA5A5' }, // red dark
          info:    { bg: '#0C4A6E', text: '#7DD3FC' }, // sky dark
          neutral: { bg: '#334155', text: '#94A3B8' }, // slate dark
        }
      }
    },
  },
  plugins: [],
}
