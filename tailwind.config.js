/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.{vue,js,ts}",
    "./resources/**/**/**/*.vue",
  ],
  safelist: [
    // keep dynamic utility classes alive
    { pattern: /(bg|text|border)-(slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900)/ },
    { pattern: /(from|to|via)-(.*)-(100|200|300|400|500|600)/ },
    "bg-primary","text-primary","bg-primaryLight","shadow-card","shadow-soft",
  ],
  theme: {
    extend: {
      colors: {
        // added tokens (non-destructive)
        primary: "#5B61F4",
        primaryLight: "#EEF0FE",
        dashboardBg: "#F5F7FB",
        cardBg: "#FFFFFF",
        textPrimary: "#1C1E21",
        textSecondary: "#7A7A7A",
        success: "#00B074",
        danger: "#E63946",
        warn: "#FFB703",
        borderLight: "#E8EAF1",
      },
      borderRadius: {
        xl: "16px",
        "2xl": "24px",
      },
      boxShadow: {
        soft: "0 4px 20px rgba(0,0,0,0.05)",
        card: "0 2px 10px rgba(0,0,0,0.04)",
      },
      fontFamily: {
        sans: ["Poppins","Inter","Nunito Sans","ui-sans-serif","system-ui"],
      },
    },
  },
  plugins: [],
}
