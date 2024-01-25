<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import OuterLayoutContainer from "@/Components/OuterLayoutContainer.vue";
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="surface-overlay py-4 shadow-1">
                <OuterLayoutContainer>
                    <h2 class="m-0">Dashboard</h2>
                </OuterLayoutContainer>
            </div>
        </template>
        <div class="mt-5 surface-overlay p-5 shadow-1 border-noround md:border-round-lg">
            <div>
                <p class="m-0">You are logged in!</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
