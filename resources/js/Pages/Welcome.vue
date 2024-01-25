<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import GuestLayout from "@/Layouts/GuestLayout.vue";

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

    <GuestLayout>
        <div class="h-screen flex align-items-center justify-content-center">
            <div
                class="grid grid-nogutter surface-section text-800 border-round shadow-1"
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
                            <a href="https://laravel.com/docs/master/fortify"
                                >Laravel Fortify</a
                            >
                            for authentication,
                            <a href="https://primevue.org/">PrimeVue</a> and
                            <a href="https://primeflex.org/">PrimeFlex</a> for
                            the UI, and
                            <a href="https://inertiajs.com/">Inertia.js</a> as
                            the glue.
                        </p>

                        <Link :href="route('login')" class="p-menuitem-link">
                            <Button
                                label="Login"
                                icon="pi pi-sign-in"
                                class="mr-3 p-button-raised"
                            ></Button>
                        </Link>
                        <Link :href="route('register')" class="p-menuitem-link">
                            <Button
                                label="Register"
                                icon="pi pi-user-plus"
                                class="p-button-outlined"
                            ></Button>
                        </Link>
                    </section>
                </div>
            </div>
        </div>
    </GuestLayout>

    <!-- <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-700 text-center">
            <div class="text-900 font-bold text-5xl mb-3">
                PrimeVue With Laravel Starter Kit
            </div>
            <div class="text-700 text-2xl mb-5">
                Laravel authentication powered by Fortify, UI powered by
                PrimeVue and PrimeFlex.
            </div>
            <Link :href="route('login')" class="p-menuitem-link">
                <Button
                    label="Login"
                    icon="pi pi-sign-in"
                    class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap mr-3"
                ></Button>
            </Link>
            <Link :href="route('register')" class="p-menuitem-link">
                <Button
                    label="Register"
                    icon="pi pi-user-plus"
                    class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"
                ></Button>
            </Link>
        </div>
    </div> -->
</template>

<style>
body {
    background: #fff !important;
}
</style>
