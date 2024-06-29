<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ThemeToggleButton from "@/Components/ThemeToggleButton.vue";

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

    <div class="grid-nogutter h-full">
        <div
            class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2 h-full"
        >
            <div
                class="h-screen flex align-items-center justify-content-center"
            >
                <div
                    class="grid grid-nogutter surface-section text-800 border-round-lg shadow-1 surface-overlay"
                >
                    <div
                        class="col-12 md:col-8 p-6 text-center md:text-left flex align-items-center"
                    >
                        <section>
                            <span class="block text-6xl font-bold mb-1"
                                >Laravel & Inertia</span
                            >
                            <div class="text-6xl text-primary font-bold mb-3">
                                PrimeVue & PrimeFlex
                            </div>
                            <p class="mt-0 mb-4 text-700 line-height-3">
                                A starter kit using
                                <a
                                    href="https://laravel.com/docs/master/starter-kits#laravel-breeze"
                                    >Laravel Breeze</a
                                >
                                for authentication,
                                <a href="https://v3.primevue.org/">PrimeVue</a> and
                                <a href="https://primeflex.org/">PrimeFlex</a>
                                for the UI, and
                                <a href="https://inertiajs.com/">Inertia.js</a>
                                as the glue.
                            </p>

                            <template v-if="$page.props.auth.user">
                                <Link
                                    :href="route('dashboard')"
                                    class="p-menuitem-link"
                                >
                                    <Button
                                        raised
                                        label="Dashboard"
                                        icon="pi pi-home"
                                        class="mr-3"
                                    />
                                </Link>
                                <Link
                                    :href="route('profile.edit')"
                                    class="p-menuitem-link"
                                >
                                    <Button
                                        outlined
                                        label="Profile"
                                        icon="pi pi-user"
                                        class="mr-3"
                                    />
                                </Link>
                            </template>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="p-menuitem-link"
                                >
                                    <Button
                                        raised
                                        label="Login"
                                        icon="pi pi-sign-in"
                                        class="mr-3"
                                    />
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="p-menuitem-link"
                                >
                                    <Button
                                        outlined
                                        label="Register"
                                        icon="pi pi-user-plus"
                                        class="mr-3"
                                    />
                                </Link>
                            </template>
                            <ThemeToggleButton outlined />
                            <div class="mt-4">
                                <p class="m-0 text-sm text-color-secondary">
                                    Laravel v{{ laravelVersion }} (PHP v{{
                                        phpVersion
                                    }})
                                </p>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
body {
    background: #fff !important;
}
</style>
