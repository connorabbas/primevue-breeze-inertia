<script setup>
import { ref, onMounted } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Message from 'primevue/message';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const emailInput = ref(null);

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};

onMounted(() => {
    emailInput.value.$el.focus();
});
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="w-full sm:w-full md:w-[30rem] mb-4">
            <Message
                v-if="status"
                severity="success"
                :closable="false"
                class="shadow"
            >
                {{ status }}
            </Message>
        </div>

        <div
            class="bg-surface-0 dark:bg-surface-900 p-6 shadow rounded-lg w-full sm:w-full md:w-[30rem]"
        >
            <div class="mb-6 text-sm text-muted-color">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            <form @submit.prevent="submit">
                <div class="mb-6">
                    <label for="email" class="block mb-2">Email</label>
                    <InputText
                        required
                        ref="emailInput"
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        :class="form.errors.email ? 'p-invalid' : ''"
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
        </div>
    </GuestLayout>
</template>
