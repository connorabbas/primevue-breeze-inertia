# Laravel Inertia & PrimeVue Starter Kit
A basic starter kit using [Laravel](https://laravel.com/docs/master), [Intertia.js](https://inertiajs.com/), and [PrimeVue](https://primevue.org/). An equivalent to using [Laravel Breeze](https://laravel.com/docs/master/starter-kits#laravel-breeze), but with the added benefit of all the PrimeVue components at your disposal.

## Usage with Docker
This starter kit is configured to use Docker Compose for local development with a few extra configuration steps. With this setup, you do not need PHP, Composer, MySQL or Node.js installed on your machine to get up and running with this project.

### Setup
1. In a new directory (outside of your Laravel project) create a `docker-compose.yml` file to create a reverse proxy container using [Traefik](https://doc.traefik.io/traefik/getting-started/quick-start/). You can reference this [example implementation](https://github.com/connorabbas/traefik-docker-compose/blob/master/docker-compose.yml).

2. Step into the directory containing the new compose file, and spin up the Traefik container:
    ```
    docker compose up -d
    ```
3. Update Laravel app `.env`
    ```env
    # Use any desired domain ending with .localhost
    # Match with value used in docker-compose.local.yml
    APP_URL=http://primevue-inertia.localhost

    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=sail
    DB_PASSWORD=password

    # Update as needed for running multiple projects
    APP_PORT=8000
    VITE_PORT=5173
    FORWARD_DB_PORT=3306
    ```
3. Build the Laravel app container:
   - Either build manually with docker compose (like above), use [Laravel Sail](https://laravel.com/docs/master/sail), or build as a [VS Code Dev Container](https://code.visualstudio.com/docs/devcontainers/tutorial) using the `Dev Containers: Reopen in Container` command.

### Additional configuration
If you wish to add additional services, or swap out MySQL with an alternative database, you can reference the [Laravel Sail stubs](https://github.com/laravel/sail/tree/1.x/stubs) and update the `docker-compose.local.yml` file as needed.

## Admin Area
### About
This branch provides a separate `admin` auth guard for admins to login to the "backend" of the application.

The following features are provided:
- Admin registration via artisan command
- Fully integrated authentication features (same as standard User model)
    - Forgot / reset password flow
    - Email verification flow with middleware
    - Profile page to manage account settings
    - Tests to cover all authentication / authorization / profile update processes
- Separate Admin Layout
- Example data index page (using registered User model data)

### Register new Admin User
Since there is no registration page for admins, use the following artisan command:
```
php artisan admin:create
```

Use the `/admin/login` route to login.

### User Data
An example index data page is provided at the `/admin/users` route. To seed with 100 test Users (locally):
```
php artisan db:seed
```

### Changes
[Compare against the master branch](https://github.com/connorabbas/primevue-breeze-inertia/compare/master...admin-panel)

## Theme
This starter kit provides a light/dark mode and custom theme functionality provided by the powerful PrimeVue theming system, using styled mode and custom design token values.

The starting point for customizing your theme will be the `resources/js/theme-preset.js` module file. To quickly change the look and feel of your theme, swap the [primary](https://primevue.org/theming/styled/#primary) values with a different set of [colors](https://primevue.org/theming/styled/#colors), change the [surface](https://primevue.org/theming/styled/#surface) `colorScheme` values (slate, gray, neutral, etc.), or completely change the [preset theme](https://primevue.org/theming/styled/#presets) (Aura used by default).

Please reference the [PrimeVue Styled Mode Docs](https://primevue.org/theming/styled/) to fully understand how this system works, and how to further customize your theme to make it your own.

## PrimeVue v4 w/ Tailwind CSS
If you have used a previous version of this project using PrimeVue v3, you'll know that Tailwind was removed in favor of PrimeFlex. With v4 however, the PrimeTek team has officially suggested [Moving from PrimeFlex to Tailwind CSS](https://primevue.org/guides/primeflex/).

For this reason, Tailwind has been added back into the project and is utilized with the [tailwindcss-primeui](https://primevue.org/tailwind/#plugin) plugin. CSS layers have also been implemented so the Tailwind utilities can [override](https://primevue.org/tailwind/#override) the PrimeVue component styling when needed.