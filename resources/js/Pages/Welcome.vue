<script setup>
import ResponsiveCard from '@/Components/ResponsiveCard.vue';
import ToggleThemeButton from '@/Components/ToggleThemeButton.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="grid-nogutter min-h-full">
        <div
            class="col-span-12 md:col-span-10 md:col-start-2 lg:col-span-8 lg:col-start-3 h-full"
        >
            <div class="h-screen flex items-center justify-center">
                <ResponsiveCard spacing-classes="p-4 py-6 sm:p-12">
                    <div
                        class="col-span-12 md:col-span-8 text-center md:text-left flex items-center"
                    >
                        <section>
                            <span class="block text-6xl font-bold mb-1"
                                >Laravel Breeze</span
                            >
                            <div class="text-6xl text-primary font-bold mb-4">
                                w/ PrimeVue
                            </div>
                            <p
                                class="mt-0 mb-6 text-muted-color leading-normal"
                            >
                                A starter kit using the Vue/Inertia option for
                                <a
                                    href="https://laravel.com/docs/master/starter-kits#laravel-breeze"
                                    class="underline text-primary hover:text-color"
                                    >Laravel Breeze</a
                                >
                                utilizing
                                <a
                                    href="https://primevue.org/"
                                    class="underline text-primary hover:text-color"
                                    >PrimeVue</a
                                >
                                components
                            </p>
                            <template v-if="$page.props.auth.user">
                                <Link :href="route('dashboard')">
                                    <Button
                                        raised
                                        label="Dashboard"
                                        icon="pi pi-home"
                                        class="mr-4"
                                    />
                                </Link>
                                <Link :href="route('profile.edit')">
                                    <Button
                                        outlined
                                        label="Profile"
                                        icon="pi pi-user"
                                        class="mr-4"
                                    />
                                </Link>
                            </template>
                            <template v-else>
                                <Link :href="route('login')">
                                    <Button
                                        raised
                                        label="Login"
                                        icon="pi pi-sign-in"
                                        class="mr-4"
                                    />
                                </Link>
                                <Link :href="route('register')">
                                    <Button
                                        outlined
                                        label="Register"
                                        icon="pi pi-user-plus"
                                        class="mr-4"
                                    />
                                </Link>
                            </template>
                            <ToggleThemeButton outlined />
                            <div class="mt-6">
                                <p class="m-0 text-sm text-muted-color">
                                    Laravel v{{ laravelVersion }} (PHP v{{
                                        phpVersion
                                    }})
                                </p>
                            </div>
                        </section>
                    </div>
                </ResponsiveCard>
            </div>
        </div>
    </div>
</template>
