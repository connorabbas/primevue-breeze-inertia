<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import OuterLayoutContainer from '@/Components/OuterLayoutContainer.vue';
import { Head } from '@inertiajs/vue3';

import Card from 'primevue/card';

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
            <h2 class="font-semibold text-xl leading-tight">Profile</h2>
        </template>

        <OuterLayoutContainer>
            <div class="my-12">
                <div
                    class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl mb-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>
                <div
                    class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl mb-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
                <div
                    class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl mb-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </OuterLayoutContainer>
    </AuthenticatedLayout>
</template>
