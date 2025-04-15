tailwind.config = {
    darkMode: 'media',
    theme: {
        extend: {
            colors: {
                darkBlue1: '#0f172a',
                darkBlue2: '#1f2937',
                pink1: '#ec4899',
                pink2: '#f43f5e',
                lightGray: '#d1d5db',
                mediumGray: '#9ca3af',
            },
            animation: {
                fadeIn: 'fadeIn 0.3s ease-out',
                gradientShift: 'gradientShift 32s ease infinite',
                textGradient: 'textGradient 12s ease infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': {opacity: '0', transform: 'translateY(10px)'},
                    '100%': {opacity: '1', transform: 'translateY(0)'},
                },
                gradientShift: {
                    '0%': {backgroundPosition: '0% 50%'},
                    '50%': {backgroundPosition: '100% 50%'},
                    '100%': {backgroundPosition: '0% 50%'},
                },
                textGradient: {
                    '0%': {backgroundPosition: '0% 50%'},
                    '50%': {backgroundPosition: '100% 50%'},
                    '100%': {backgroundPosition: '0% 50%'},
                },
            },
        },
    },
    plugins: [],
}
