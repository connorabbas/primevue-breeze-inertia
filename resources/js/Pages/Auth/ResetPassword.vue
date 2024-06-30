<script setup>
import { ref, onMounted } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

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

const emailInput = ref(null);

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => {
    emailInput.value.$el.focus();
});
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div
            class="bg-surface-0 dark:bg-surface-900 p-6 shadow rounded-lg w-full sm:w-full md:w-[30rem]"
        >
            <form @submit.prevent="submit">
                <div class="mb-6">
                    <label for="email" class="block mb-2">Email</label>
                    <InputText
                        ref="emailInput"
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        :class="form.errors.email ? 'p-invalid' : ''"
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
                        :class="form.errors.password ? 'p-invalid' : ''"
                        required
                        autocomplete="new-password"
                    />
                    <InputError
                        class="mt-2 mb-1"
                        :message="form.errors.password"
                    />
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
                        :class="
                            form.errors.password_confirmation ? 'p-invalid' : ''
                        "
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
        </div>
    </GuestLayout>
</template>
