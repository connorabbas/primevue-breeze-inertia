<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import OuterLayoutContainer from "@/Components/OuterLayoutContainer.vue";
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="bg-surface-0 dark:bg-surface-900 py-6 shadow-sm">
                <OuterLayoutContainer>
                    <h2 class="m-0">Dashboard</h2>
                </OuterLayoutContainer>
            </div>
        </template>
        <div class="my-12 bg-surface-0 dark:bg-surface-900 p-8 shadow-sm rounded-none sm:rounded-lg">
            <div>
                <p class="m-0">You are logged in!</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
