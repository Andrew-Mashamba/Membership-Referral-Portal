import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            /* ATCL SACCOS – Official Brand */
            colors: {
                primaryBg: '#FFFFFF',
                primaryText: '#1A1A1A',
                secondaryText: '#797979',
                accent: '#797979',
                brandBlue: '#20538A',
                brandGray: '#797979',
                brandWhite: '#FFFFFF',
                iconBg: '#20538A',
            },
            borderRadius: {
                lgx: '16px',
                mdx: '12px',
            },
            boxShadow: {
                soft: '0 2px 6px rgba(0,0,0,0.08)',
                card: '0 4px 10px rgba(0,0,0,0.1)',
            },
            fontSize: {
                title: '15px',
                subtitle: '11px',
                body: ['13px', { lineHeight: '1.5' }],
            },
            spacing: {
                18: '72px',
                20: '80px',
            },
        },
    },

    plugins: [forms, typography],
};
