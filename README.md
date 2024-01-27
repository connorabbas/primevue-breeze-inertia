# Laravel & Inertia w/ PrimeVue & PrimeFlex
A starter kit using [Laravel Fortify](https://laravel.com/docs/master/fortify) for authentication, [PrimeVue](https://primevue.org/) & [PrimeFlex](https://primeflex.org/) for the UI, and [Intertia.js](https://inertiajs.com/) as the glue. Intended to be an alternative to [Laravel Breeze](https://laravel.com/docs/master/starter-kits#laravel-breeze), providing the same UI design and functionality, but built with [PrimeTek](https://github.com/primefaces) UI packages instead of Tailwind.

## Installation 
1. Clone the repo
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
4. Generate App Key
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

## Theming
### Theme Toggle
By default, the application comes with a light/dark theme toggle. In order for this theme toggle (and PrimeVue component styling) to work, the necessary theme folders must live in the `/public/themes` directory of the application.

The `lara-light-indigo` and `lara-dark-indigo` themes are provided and used by default, since they most closely resemble the Laravel Breeze styling.

> :warning: **Keep in mind:** The default css theme files provided are not minified.

### Swap Themes
You can easily change what themes are used for the light/dark modes by copying and pasting your desired theme folders from `/node_modules/primevue/resources/themes` into the `/public/themes` directory

Then within the `/resources/js/Composables/useTheme.js` file you can change the values of the `lightTheme` and `darkTheme` const variables accordingly. These values should exactly match the directory names of the themes you intend on using.

In theory, you can use any combination of themes you want with the toggle, or expand on the `useTheme()` composable further to allow more then 2 themes to be selected on your site.

[PrimeVue Themes](https://primevue.org/theming/#builtinthemes)