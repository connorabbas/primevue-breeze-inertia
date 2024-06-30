<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import OuterLayoutContainer from "@/Components/OuterLayoutContainer.vue";
import { Head } from "@inertiajs/vue3";

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
            <div class="bg-surface-0 dark:bg-surface-900 py-6 shadow-sm">
                <OuterLayoutContainer>
                    <h2 class="m-0">Profile</h2>
                </OuterLayoutContainer>
            </div>
        </template>

        <div class="my-12">
            <div
                class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow-sm rounded-none sm:rounded-lg mb-8"
            >
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div
                class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow-sm rounded-none sm:rounded-lg mb-8"
            >
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div
                class="p-4 sm:p-8 bg-surface-0 dark:bg-surface-900 shadow-sm rounded-none sm:rounded-lg mb-8"
            >
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
