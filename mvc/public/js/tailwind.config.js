tailwind.config = {
    darkMode: 'media',
    theme: {
        extend: {
            animation: {
                fadeIn: 'fadeIn 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    }

}