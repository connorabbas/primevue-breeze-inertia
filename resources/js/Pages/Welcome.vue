<script setup>
import { computed } from 'vue';
import ToggleThemeButton from '@/Components/ToggleThemeButton.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    isAdmin: {
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

const dashboardRoute = computed(() => {
    return props.isAdmin ? route('admin.dashboard') : route('dashboard');
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-full">
        <div class="h-screen flex items-center justify-center">
            <Card
                :pt="{
                    body: {
                        class: 'p-4 py-6 sm:p-12',
                    },
                }"
            >
                <template #content>
                    <div class="text-center md:text-left">
                        <span class="block text-6xl font-bold mb-1"
                            >Laravel Breeze</span
                        >
                        <div class="text-6xl text-primary font-bold mb-4">
                            & PrimeVue
                        </div>
                        <p class="mt-0 mb-1 text-muted-color leading-normal">
                            A starter kit using the Vue/Inertia option for
                            <a
                                href="https://laravel.com/docs/master/starter-kits#laravel-breeze"
                                class="underline text-primary hover:text-color"
                                >Laravel Breeze</a
                            >,
                        </p>
                        <p class="mt-0 mb-6 text-muted-color leading-normal">
                            utilizing
                            <a
                                href="https://primevue.org/"
                                class="underline text-primary hover:text-color"
                                >PrimeVue</a
                            >
                            components
                        </p>
                        <template v-if="$page.props.auth.user">
                            <Link :href="dashboardRoute">
                                <Button
                                    raised
                                    label="Dashboard"
                                    icon="pi pi-th-large"
                                    class="mr-4"
                                />
                            </Link>
                            <Link v-if="!isAdmin" :href="route('profile.edit')">
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
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>
