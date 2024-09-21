<script setup>
import { useTemplateRef, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminGuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const emailInput = useTemplateRef('email-input');

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => {
    emailInput.value.$el.focus();
});
</script>

<template>
    <AdminGuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div class="mb-6">
                <label for="email" class="block mb-2">Email</label>
                <InputText
                    ref="email-input"
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="w-full"
                    :invalid="Boolean(form.errors.email)"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2">Password</label>
                <InputText
                    id="password"
                    type="password"
                    v-model="form.password"
                    class="w-full"
                    :invalid="Boolean(form.errors.password)"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 mb-1" :message="form.errors.password" />
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2"
                    >Password</label
                >
                <InputText
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    class="w-full"
                    :invalid="Boolean(form.errors.password_confirmation)"
                    required
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2 mb-1"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex justify-end items-center">
                <Button
                    raised
                    type="submit"
                    :loading="form.processing"
                    label="Reset Password"
                    severity="contrast"
                />
            </div>
        </form>
    </AdminGuestLayout>
</template>
