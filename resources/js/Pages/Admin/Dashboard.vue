<script setup>
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedAdminLayout>
        <Container :spaced-mobile="false">
            <div class="py-12">
                <ResponsiveCard>
                    <p class="m-0">You are logged in as an Admin!</p>
                </ResponsiveCard>
            </div>
        </Container>
    </AuthenticatedAdminLayout>
</template>
