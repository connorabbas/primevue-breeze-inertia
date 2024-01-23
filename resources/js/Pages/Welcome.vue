<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Button from 'primevue/button';

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

    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="text-700 text-center">
            <div class="text-900 font-bold text-5xl mb-3">
                PrimeVue With Laravel Starter Kit
            </div>
            <div class="text-700 text-2xl mb-5">
                Laravel authentication powered by Fortify, UI with PrimeVue and PrimeFlex.
            </div>
            <Button
                label="Login"
                icon="pi pi-sign-in"
                class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap mr-3"
            ></Button>
            <Button
                label="Register"
                icon="pi pi-user-plus"
                class="font-bold px-5 py-3 p-button-raised p-button-rounded white-space-nowrap"
            ></Button>
        </div>
    </div>
</template>

<style></style>
