<script setup>
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';

const pageTitle = 'Dashboard';
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedAdminLayout>
        <Container :fluid="true" class="py-4 md:py-8">
            <div>
                <Card>
                    <template #content>
                        <p class="m-0">You are logged in as an Admin!</p>
                    </template>
                </Card>
            </div>
        </Container>
    </AuthenticatedAdminLayout>
</template>
