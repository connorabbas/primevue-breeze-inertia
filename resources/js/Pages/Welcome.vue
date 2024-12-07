<script setup>
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
                        <span class="block text-6xl font-bold text-red-500 dark:text-red-400 mb-1 ">Laravel,</span>
                        <div class="text-6xl font-bold text-indigo-500 dark:text-indigo-400 mb-1">Inertia.js,</div>
                        <div class="text-6xl text-green-500 dark:text-green-400 text-emerald font-bold mb-4">& PrimeVue</div>
                        <p class="mt-0 mb-4 text-muted-color leading-normal">
                            A starter kit using
                            <a
                                href="https://laravel.com/docs/master"
                                class="underline text-primary hover:text-color"
                                >Laravel</a
                            >,
                            <a
                                href="https://inertiajs.com/"
                                class="underline text-primary hover:text-color"
                                >Inertia.js</a
                            >,
                            and
                            <a
                                href="https://primevue.org/"
                                class="underline text-primary hover:text-color"
                                >PrimeVue</a
                            >.
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
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>
