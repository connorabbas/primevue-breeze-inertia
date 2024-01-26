<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import Button from "primevue/button";
import InputError from "@/Components/InputError.vue";
import Message from "primevue/message";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="w-full sm:w-12 md:w-30rem mb-3">
            <Message v-if="status" severity="success" :closable="false">
                {{ status }}
            </Message>
        </div>

        <div
            class="surface-card p-4 shadow-1 border-round-lg w-full sm:w-12 md:w-30rem"
        >
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

                <div class="mb-4">
                    <label for="password" class="block mb-2">Password</label>
                    <InputText
                        required
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full"
                        autocomplete="current-password"
                    />
                    <InputError
                        class="mt-2 mb-1"
                        :message="form.errors.password"
                    />
                </div>

                <div class="mb-5">
                    <div
                        class="flex align-items-center justify-content-between"
                    >
                        <div class="flex align-items-center">
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

                <div class="flex justify-content-end align-items-center">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="mr-3 text-color-secondary hover:text-color"
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
        </div>
    </GuestLayout>
</template>
