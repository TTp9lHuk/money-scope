export default {
    content: [ './resources/js/**/*.vue', './resources/views/**/*.blade.php' ],
    theme: {
        extend: {
            colors: {
                'accent-purple': '#8b5cf6',
                'dark-bg': '#0f172a',    // Глубокий фон
                'dark-card': '#1e293b',  // Чуть светлее для карточек
                'dark-border': '#334155', // Границы
                'accent-blue': '#38bdf8',
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
