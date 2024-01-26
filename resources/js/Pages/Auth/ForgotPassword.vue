<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import Message from "primevue/message";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { Head, useForm, Link } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="w-full sm:w-12 md:w-30rem mb-3">
            <Message
                v-if="status"
                severity="success"
                :closable="false"
                class="shadow-1"
            >
                {{ status }}
            </Message>
        </div>

        <div
            class="surface-card p-4 shadow-1 border-round-lg w-full sm:w-12 md:w-30rem"
        >
            <div class="mb-4 text-sm text-color-secondary">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="email" class="block mb-2">Email</label>
                    <InputText
                        required
                        autofocus
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex justify-content-end align-items-center">
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
