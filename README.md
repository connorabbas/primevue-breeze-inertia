# Laravel & Inertia w/ PrimeVue & PrimeFlex
A starter kit using [Laravel Fortify](https://laravel.com/docs/master/fortify) for authentication, [PrimeVue](https://primevue.org/) & [PrimeFlex](https://primeflex.org/) for the UI, and [Intertia.js](https://inertiajs.com/) as the glue. Intended to be an alternative to [Laravel Breeze](https://laravel.com/docs/master/starter-kits#laravel-breeze), providing the same UI design and functionality, but built with [PrimeTek](https://github.com/primefaces) UI packages instead of Tailwind.

## Installation 
Install the framework and other packages:
```
composer install
```
Migrate database tables (after `.env` and database related config setup):
```
php artisan migrate
```
Install npm packages:
```
npm install
```
Start the Vite server:
```
npm run dev
```

## Theming
### Theme Toggle
By default the application comes with a light/dark theme toggle to switch between the `lara-light-indigo` and `lara-dark-indigo` themes, provided by PrimeVue.

In order for this theme toggle (and PrimeVue component styling) to work, the necessary css files must live in the `/public` directory of the application.

### Swap Themes
You can easily change what themes are used for the light/dark modes by copying and pasting your desired theme folders from `/node_modules/primevue/resources/themes/*` into the `/public/themes` directory. Then within the `/resources/js/Composables/useTheme.js` file you can change the values of the `lightTheme` and `darkTheme` const variables accordingly.