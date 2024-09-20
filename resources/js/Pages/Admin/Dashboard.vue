<script setup>
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';

const pageTitle = 'Dashboard';
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedAdminLayout :page-title="pageTitle">
        <Container :spaced-mobile="false">
            <div>
                <ResponsiveCard>
                    <p class="m-0">You are logged in as an Admin!</p>
                </ResponsiveCard>
            </div>
        </Container>
    </AuthenticatedAdminLayout>
</template>
