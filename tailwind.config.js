import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import animate from 'tailwindcss-animate';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,vue}',
    ],

    theme: {
        container: {
            center: true,
            padding: '2rem',
            screens: {
                '2xl': '1400px',
            },
        },
        extend: {
            fontFamily: {
                sans: ['Outfit', 'sans-serif'],
                display: ['Space Grotesk', 'sans-serif'],
            },
            colors: {
                border: 'hsl(var(--border))',
                input: 'hsl(var(--input))',
                ring: 'hsl(var(--ring))',
                background: 'hsl(var(--background))',
                foreground: 'hsl(var(--foreground))',
                primary: {
                    DEFAULT: 'hsl(var(--primary))',
                    foreground: 'hsl(var(--primary-foreground))',
                },
                secondary: {
                    DEFAULT: 'hsl(var(--secondary))',
                    foreground: 'hsl(var(--secondary-foreground))',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive))',
                    foreground: 'hsl(var(--destructive-foreground))',
                },
                muted: {
                    DEFAULT: 'hsl(var(--muted))',
                    foreground: 'hsl(var(--muted-foreground))',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent))',
                    foreground: 'hsl(var(--accent-foreground))',
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover))',
                    foreground: 'hsl(var(--popover-foreground))',
                },
                card: {
                    DEFAULT: 'hsl(var(--card))',
                    foreground: 'hsl(var(--card-foreground))',
                },
                brand: {
                    black: '#0A0A0B',
                    surface: '#121214',
                    accent: '#6366F1',
                    emerald: '#10B981',
                },
                // Nuevos colores para Neubrutalismo
                brutalist: {
                    yellow: '#FF4400',
                    pink: '#FF2E63',
                    blue: '#00F0FF',
                    lime: '#39FF14',
                    purple: '#B026FF',
                    black: '#1A1A1A',
                    white: '#F9F9F9',
                }
            },
            boxShadow: {
                'brutalist': '8px 8px 0px 0px rgba(0,0,0,1)',
                'brutalist-lg': '16px 16px 0px 0px rgba(0,0,0,1)',
                'brutalist-hover': '4px 4px 0px 0px rgba(0,0,0,1)',
                'brutalist-hover-lg': '16px 16px 0px 0px rgba(0,0,0,1)',
                'brutalist-white': '8px 8px 0px 0px rgba(255,255,255,1)',
                'brutalist-white-lg': '16px 16px 0px 0px rgba(255,255,255,1)',
                'brutalist-accent': '8px 8px 0px 0px #6366F1',
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 2px)',
                sm: 'calc(var(--radius) - 4px)',
            },
            backgroundImage: {
                'noise': "url('https://grainy-gradients.vercel.app/noise.svg')",
            },
            keyframes: {
                'accordion-down': {
                    from: { height: 0 },
                    to: { height: 'var(--radix-accordion-content-height)' },
                },
                'accordion-up': {
                    from: { height: 'var(--radix-accordion-content-height)' },
                    to: { height: 0 },
                },
            },
            animation: {
                'accordion-down': 'accordion-down 0.2s ease-out',
                'accordion-up': 'accordion-up 0.2s ease-out',
                'spin-slow': 'spin 8s linear infinite',
            },
        },
    },

    plugins: [forms, typography, animate],
};
