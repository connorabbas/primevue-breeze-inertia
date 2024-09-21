<script setup>
import { useTemplateRef, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Message from 'primevue/message';
import AdminGuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    status: {
        type: String,
    },
});

const emailInput = useTemplateRef('email-input');

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('admin.password.email'));
};

onMounted(() => {
    emailInput.value.$el.focus();
});
</script>

<template>
    <AdminGuestLayout>
        <Head title="Forgot Password" />

        <template #message v-if="status">
            <Message severity="success" :closable="false" class="shadow">
                {{ status }}
            </Message>
        </template>

        <div class="mb-6 text-sm text-muted-color">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <form @submit.prevent="submit">
            <div class="mb-6">
                <label for="email" class="block mb-2">Email</label>
                <InputText
                    required
                    ref="email-input"
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="w-full"
                    :invalid="Boolean(form.errors.email)"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex justify-end items-center">
                <Button
                    raised
                    type="submit"
                    :loading="form.processing"
                    label="Email Password Reset Link"
                    severity="contrast"
                />
            </div>
        </form>
    </AdminGuestLayout>
</template>
