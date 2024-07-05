export default {
    semantic: {
        primary: {
            50: '{indigo.50}',
            100: '{indigo.100}',
            200: '{indigo.200}',
            300: '{indigo.300}',
            400: '{indigo.400}',
            500: '{indigo.500}',
            600: '{indigo.600}',
            700: '{indigo.700}',
            800: '{indigo.800}',
            900: '{indigo.900}',
            950: '{indigo.950}',
        },
        colorScheme: {
            light: {
                surface: {
                    50: '{gray.50}',
                    100: '{gray.100}',
                    200: '{gray.200}',
                    300: '{gray.300}',
                    400: '{gray.400}',
                    500: '{gray.500}',
                    600: '{gray.600}',
                    700: '{gray.700}',
                    800: '{gray.800}',
                    900: '{gray.900}',
                    950: '{gray.950}',
                },
            },
            dark: {
                surface: {
                    50: '{gray.50}',
                    100: '{gray.100}',
                    200: '{gray.200}',
                    300: '{gray.300}',
                    400: '{gray.400}',
                    500: '{gray.500}',
                    600: '{gray.600}',
                    700: '{gray.700}',
                    800: '{gray.800}',
                    900: '{gray.900}',
                    950: '{gray.950}',
                },
                /**
                 * If you want to perfectly match the Laravel breeze dark mode styling (slightly lighter):
                 * 1. Search and replace dark:bg-surface-900 with dark:bg-surface-800
                 * 2. Search and replace dark:bg-surface-950 with dark:bg-surface-900
                 * 3. Uncomment the below formField color token change (matches input field bg with site bg)
                 */
                /* formField: {
                    background: '{surface.900}',
                }, */
            },
        },
    },
};
