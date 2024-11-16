<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
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
        <Container :fluid="true">
            <div class="space-y-6">
                <Card
                    :pt="{
                        body: {
                            class: 'max-w-2xl space-y-3',
                        },
                        caption: {
                            class: 'space-y-1',
                        },
                    }"
                >
                    <template #title>Profile Information</template>
                    <template #subtitle>
                        Update your account's profile information and email
                        address.
                    </template>
                    <template #content>
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </template>
                </Card>

                <Card
                    :pt="{
                        body: {
                            class: 'max-w-2xl space-y-3',
                        },
                        caption: {
                            class: 'space-y-1',
                        },
                    }"
                >
                    <template #title>Update Password</template>
                    <template #subtitle>
                        Ensure your account is using a long, random password to
                        stay secure.
                    </template>
                    <template #content>
                        <UpdatePasswordForm />
                    </template>
                </Card>

                <Card
                    :pt="{
                        body: {
                            class: 'max-w-2xl space-y-3',
                        },
                        caption: {
                            class: 'space-y-1',
                        },
                    }"
                >
                    <template #title>Delete Account</template>
                    <template #subtitle>
                        Once your account is deleted, all of its resources and
                        data will be permanently deleted. Before deleting your
                        account, please download any data or information that
                        you wish to retain.
                    </template>
                    <template #content>
                        <DeleteUserForm />
                    </template>
                </Card>
            </div>
        </Container>
    </AdminAuthenticatedLayout>
</template>
