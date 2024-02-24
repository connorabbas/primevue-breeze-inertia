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

7. Build assets (required to get theme files)
   ```
   npm run build
   ```

8. Start the Vite dev server (if developing)
   ```
   npm run dev
   ```

## Theming
### Public Theme Files
During the installation steps, you are required to run the Vite build process (`npm run build`). The reason for running the build process locally is to gather the necessary theme files for the application, which are copied into the site's `/public/themes` directory via the [vite-plugin-static-copy](https://github.com/sapphi-red/vite-plugin-static-copy#readme) plugin in the `vite.config.js` file.

The alternative to this approach would be manually copying the theme folders from `node_modules/primevue/resources/themes` into the `public/themes` directory, and committing the folders/files to your repository source code. (Would require removing the `.gitignore` rule as well)

### Changing Themes
Both the file copying process, and the light/dark theme toggle functionality rely on the `LIGHT_THEME` and `DARK_THEME` constant values defined in the `/resources/js/Modules/constants.mjs` file.

To change what themes are used on the site for light and dark modes, simply modify the constant string values to any theme name that is available from [PrimeVue's Built-in Themes](https://primevue.org/theming/#builtinthemes). After changing the values to your desired themes, you will need to run the `npm run build` command again, to copy the theme files for use.

The `lara-light-indigo` and `lara-dark-indigo` themes are used by default, since they most closely resemble the Laravel Breeze styling.