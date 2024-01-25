<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import Button from "primevue/button";
import InputError from "@/Components/InputError.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
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

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="h-screen flex align-items-center justify-content-center">
            <div
                class="surface-card p-4 shadow-1 border-round w-full sm:w-12 md:w-30rem"
            >
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label
                            for="email"
                            class="block text-900 font-medium mb-2"
                            >Email</label
                        >
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
                        <label
                            for="password"
                            class="block text-900 font-medium mb-2"
                            >Password</label
                        >
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
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="font-medium no-underline ml-2 text-blue-500 text-right cursor-pointer"
                            >
                                Forgot your password?
                            </Link>
                        </div>
                    </div>

                    <Button
                        type="submit"
                        :loading="form.processing"
                        label="Log In"
                        class="w-full"
                    ></Button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
