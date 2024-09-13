<script setup>
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';
import DataTable from './Partials/DataTable.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object,
    users: [Array, Object],
    paginateSize: [Number, String],
});
</script>

<template>
    <Head title="Users" />
    <AuthenticatedAdminLayout>
        <template #header>
            <h2 class="font-bold text-xl leading-tight">Users</h2>
        </template>

        <Container :spaced-mobile="false">
            <div class="py-12">
                <ResponsiveCard>
                    <DataTable :users="users" :paginateSize="paginateSize" />
                </ResponsiveCard>
            </div>
        </Container>
    </AuthenticatedAdminLayout>
</template>
