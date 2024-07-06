# Laravel Breeze & PrimeVue
A starter kit using [Laravel Breeze](https://laravel.com/docs/master/starter-kits#laravel-breeze) with the [Intertia.js](https://inertiajs.com/) Vue option, utilizing [PrimeVue v4](https://primevue.org/) components.

![image](https://github.com/connorabbas/primevue-auth-starter/assets/89364288/825f9296-ee84-428f-9937-ff1a16b53d53)

## Installation 
1. Clone the repo (or download the zip)
   ```
   git clone https://github.com/connorabbas/primevue-auth-starter.git
   ```

2. Step into the project directory
   ```
   cd ./primevue-auth-starter
   ```

3. Install the framework and other packages
   ```
   composer install
   ```

3. Setup `.env` file

   Windows
   ```
   copy .env.example .env
   ```
   Unix/Linux/MacOS
   ```
   cp .env.example .env
   ```

4. Generate the app key
   ```
   php artisan key:generate
   ```

5. Migrate database tables (after `.env` and database related config setup)
   ```
   php artisan migrate
   ```

6. Install npm packages
   ```
   npm install
   ```

7. Start the Vite dev server
   ```
   npm run dev
   ```

## Theme
This starter kit provides a light/dark mode and custom theme functionality provided by the powerful PrimeVue theming system, using styled mode and custom design token values.

The starting point for customizing your theme will be the `resources\js\Modules\theme-preset.mjs` module file. To quickly change the look and feel of your theme, swap the [primary](https://primevue.org/theming/styled/#primary) values with a different set of [colors](https://primevue.org/theming/styled/#colors), change the [surface](https://primevue.org/theming/styled/#surface) `colorScheme` values (slate, gray, neutral, etc.), or completely change the [preset theme](https://primevue.org/theming/styled/#presets) (Aura used by default).

Please reference the [PrimeVue Styled Mode Docs](https://primevue.org/theming/styled/) to fully understand how this system work, and how to further customize your theme to make it your own.
