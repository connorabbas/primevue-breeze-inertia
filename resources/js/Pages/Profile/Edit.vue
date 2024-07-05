<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Container from '@/Components/Container.vue';
import { Head } from '@inertiajs/vue3';

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

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl leading-tight">Profile</h2>
        </template>

        <Container :spaced-mobile="false">
            <div class="py-12">
                <div class="space-y-6">
                    <div
                        class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl"
                    >
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>

                    <div
                        class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl"
                    >
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>

                    <div
                        class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl"
                    >
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
