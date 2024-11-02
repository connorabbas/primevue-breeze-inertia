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

const pageTitle = 'Profile';
const breadcrumbs = [
    { label: 'Dashboard', route: route('admin.dashboard') },
    { label: pageTitle, route: route('admin.profile.edit') },
    { label: 'Edit' },
];
</script>

<template>
    <Head :title="pageTitle" />

    <AdminAuthenticatedLayout
        :page-title="pageTitle"
        :breadcrumbs="breadcrumbs"
    >
        <Container>
            <div class="">
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
