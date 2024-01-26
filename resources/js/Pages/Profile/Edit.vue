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
            <div class="surface-overlay py-4 shadow-1">
                <OuterLayoutContainer>
                    <h2 class="m-0">Profile</h2>
                </OuterLayoutContainer>
            </div>
        </template>

        <div class="my-6">
            <div
                class="p-4 sm:p-5 surface-overlay shadow-1 border-noround sm:border-round-lg mb-5"
            >
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div
                class="p-4 sm:p-5 surface-overlay shadow-1 border-noround sm:border-round-lg mb-5"
            >
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div
                class="p-4 sm:p-5 surface-overlay shadow-1 border-noround sm:border-round-lg mb-5"
            >
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
