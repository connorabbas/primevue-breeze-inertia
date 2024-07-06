import Aura from '@primevue/themes/aura';
import { definePreset } from '@primevue/themes';

const presetOptions = {
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
                    50: '{zinc.50}',
                    100: '{zinc.100}',
                    200: '{zinc.200}',
                    300: '{zinc.300}',
                    400: '{zinc.400}',
                    500: '{zinc.500}',
                    600: '{zinc.600}',
                    700: '{zinc.700}',
                    800: '{zinc.800}',
                    900: '{zinc.900}',
                    950: '{zinc.950}',
                },
            },
            dark: {
                surface: {
                    50: '{zinc.50}',
                    100: '{zinc.100}',
                    200: '{zinc.200}',
                    300: '{zinc.300}',
                    400: '{zinc.400}',
                    500: '{zinc.500}',
                    600: '{zinc.600}',
                    700: '{zinc.700}',
                    800: '{zinc.800}',
                    900: '{zinc.900}',
                    950: '{zinc.950}',
                },
                /**
                 * If you want to (mostly) match the Laravel breeze dark mode styling:
                 * 1. Change existing preset theme from 'Aura' to 'Lara'
                 * 2. Change the above surface color scheme tokens from 'zinc' to 'gray'
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

const themePreset = definePreset(Aura, presetOptions); // Customize an existing theme preset

export default themePreset;