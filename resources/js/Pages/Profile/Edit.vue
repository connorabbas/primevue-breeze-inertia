<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl leading-tight">Profile</h2>
        </template>

        <Container>
            <div class="py-12">
                <div class="space-y-6">
                    <Card>
                        <template #content>
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </template>
                    </Card>
                    <Card>
                        <template #content>
                            <UpdatePasswordForm class="max-w-xl" />
                        </template>
                    </Card>
                    <Card>
                        <template #content>
                            <DeleteUserForm class="max-w-xl" />
                        </template>
                    </Card>
                </div>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>
