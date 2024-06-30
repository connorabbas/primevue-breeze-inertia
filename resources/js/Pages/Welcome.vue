<script setup>
import { Head, Link } from "@inertiajs/vue3";
import ThemeToggleButton from "@/Components/ThemeToggleButton.vue";
import Card from "primevue/card";

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
                <Card>
                    <template #content>
                        <div
                            class="col-span-12 md:col-span-8 p-12 text-center md:text-left flex items-center"
                        >
                            <section>
                                <span class="block text-6xl font-bold mb-1"
                                    >Laravel & Inertia</span
                                >
                                <div
                                    class="text-6xl text-primary font-bold mb-4"
                                >
                                    PrimeVue & PrimeFlex
                                </div>
                                <p
                                    class="mt-0 mb-6 text-surface-700 dark:text-surface-100 leading-normal"
                                >
                                    A starter kit using
                                    <a
                                        href="https://laravel.com/docs/master/starter-kits#laravel-breeze"
                                        >Laravel Breeze</a
                                    >
                                    for authentication,
                                    <a href="https://v3.primevue.org/"
                                        >PrimeVue</a
                                    >
                                    and
                                    <a href="https://primeflex.org/"
                                        >PrimeFlex</a
                                    >
                                    for the UI, and
                                    <a href="https://inertiajs.com/"
                                        >Inertia.js</a
                                    >
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
                                            class="mr-4"
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
                                            class="mr-4"
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
                                            class="mr-4"
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
                                            class="mr-4"
                                        />
                                    </Link>
                                </template>
                                <ThemeToggleButton outlined />
                                <div class="mt-6">
                                    <p class="m-0 text-sm text-muted-color">
                                        Laravel v{{ laravelVersion }} (PHP v{{
                                            phpVersion
                                        }})
                                    </p>
                                </div>
                            </section>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>
