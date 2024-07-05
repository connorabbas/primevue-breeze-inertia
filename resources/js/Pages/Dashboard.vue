<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl leading-tight">Dashboard</h2>
        </template>
        <Container :spaced-mobile="false">
            <div class="py-12">
                <div
                    class="bg-surface-0 dark:bg-surface-900 p-8 shadow rounded-none sm:rounded-xl"
                >
                    <p class="m-0">You are logged in!</p>
                </div>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
