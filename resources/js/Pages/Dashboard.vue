<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const visible = ref(false);
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="border-bottom-1 border-200 bg-white py-4">
                <div class="grid-nogutter">
                    <div
                        class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2"
                    >
                        <h2 class="m-0">Dashboard</h2>
                    </div>
                </div>
            </div>
        </template>
        <div class="mt-5 surface-card p-4 shadow-1 border-round">
            <div>
                <p class="m-0">You are logged in!</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
