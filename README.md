# Laravel & Inertia w/ PrimeVue & PrimeFlex
A starter kit using [Laravel Fortify](https://laravel.com/docs/master/fortify) for authentication, [PrimeVue](https://primevue.org/) & [PrimeFlex](https://primeflex.org/) for the UI, and [Intertia.js](https://inertiajs.com/) as the glue.

## Installation 
Install the framework and other packages:
```
composer install
```
Migrate database tables (after `.env` setup):
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

## Custom Themes
By default the application comes with a light/dark theme toggle to switch between the `lara-light-indigo` and `lara-dark-indigo` theme style sheets provided by PrimeVue.

In order for this theme toggle to work, the necessary css file must live in the `/public` directory of the application, so we can successfully swap the source path dynamically.

