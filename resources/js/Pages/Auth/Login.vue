<script setup>
import { ref, onMounted } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from 'primevue/checkbox';
import InputError from '@/Components/InputError.vue';
import Message from 'primevue/message';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const emailInput = ref(null);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

onMounted(() => {
    emailInput.value.$el.focus();
});
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <template #message v-if="status">
            <Message
                severity="success"
                :closable="false"
                class="shadow"
            >
                {{ status }}
            </Message>
        </template>

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
                    :invalid="Boolean(form.errors.email)"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2">Password</label>
                <InputText
                    required
                    id="password"
                    type="password"
                    v-model="form.password"
                    class="w-full"
                    :invalid="Boolean(form.errors.password)"
                    autocomplete="current-password"
                />
                <InputError class="mt-2 mb-1" :message="form.errors.password" />
            </div>

            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <Checkbox
                            id="remember"
                            :binary="true"
                            v-model="form.remember"
                            class="mr-2"
                        ></Checkbox>
                        <label for="remember">Remember me</label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="mr-4 underline text-muted-color hover:text-color"
                >
                    Forgot your password?
                </Link>
                <Button
                    raised
                    type="submit"
                    :loading="form.processing"
                    label="Log In"
                    severity="contrast"
                />
            </div>
        </form>
    </GuestLayout>
</template>
