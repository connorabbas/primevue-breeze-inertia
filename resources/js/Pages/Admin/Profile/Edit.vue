<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

const props = defineProps({
    auth: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AdminAuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl leading-tight">Profile</h2>
        </template>

        <Container :spaced-mobile="false">
            <div class="py-12">
                <div class="space-y-6">
                    <ResponsiveCard>
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </ResponsiveCard>

                    <ResponsiveCard>
                        <UpdatePasswordForm class="max-w-xl" />
                    </ResponsiveCard>

                    <ResponsiveCard>
                        <DeleteUserForm class="max-w-xl" />
                    </ResponsiveCard>
                </div>
            </div>
        </Container>
    </AdminAuthenticatedLayout>
</template>
