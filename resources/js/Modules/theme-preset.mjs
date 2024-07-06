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
                    50: '{neutral.50}',
                    100: '{neutral.100}',
                    200: '{neutral.200}',
                    300: '{neutral.300}',
                    400: '{neutral.400}',
                    500: '{neutral.500}',
                    600: '{neutral.600}',
                    700: '{neutral.700}',
                    800: '{neutral.800}',
                    900: '{neutral.900}',
                    950: '{neutral.950}',
                },
            },
            dark: {
                surface: {
                    50: '{neutral.50}',
                    100: '{neutral.100}',
                    200: '{neutral.200}',
                    300: '{neutral.300}',
                    400: '{neutral.400}',
                    500: '{neutral.500}',
                    600: '{neutral.600}',
                    700: '{neutral.700}',
                    800: '{neutral.800}',
                    900: '{neutral.900}',
                    950: '{neutral.950}',
                },
                /**
                 * If you want to (mostly) match the Laravel breeze dark mode styling:
                 * 1. Change theme to 'Lara' within the app.js file
                 * 2. Change the above surface color scheme tokens from 'neutral' to 'gray'
                 * 3. Within the project, search for dark:bg-surface-900 and replace with dark:bg-surface-800
                 * 4. Within the project, search for dark:bg-surface-950 and replace with dark:bg-surface-900
                 * 5. Uncomment the below formField color token change (matches input field bg with site bg)
                 */
                /* formField: {
                    background: '{surface.900}',
                }, */
            },
        },
    },
};
