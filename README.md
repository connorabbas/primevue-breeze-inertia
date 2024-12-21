# Laravel, Inertia, & PrimeVue Starter Kit

## Admin Panel

This branch provides a separate `admin` auth guard for admins to login to the "backend" of the application.

The following features are provided:

-   Admin registration via artisan command
-   Fully integrated authentication features (same as standard User model)
    -   Forgot / reset password flow
    -   Email verification flow with middleware
    -   Profile page to manage account settings
    -   Tests to cover all authentication / authorization / profile update processes
-   Separate Admin Layout
-   Example data index page (using registered User model data)

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

[Compare against the master branch](https://github.com/connorabbas/primevue-breeze-inertia/compare/master...feature/admin-panel)
