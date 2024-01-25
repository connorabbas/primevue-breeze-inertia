<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="h-screen flex align-items-center justify-content-center">
            <div
                class="surface-card p-4 shadow-1 border-round w-full sm:w-12 md:w-30rem"
            >
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label
                            for="name"
                            class="block text-900 font-medium mb-2"
                            >Name</label
                        >
                        <InputText
                            autofocus
                            id="name"
                            type="text"
                            v-model="form.name"
                            class="w-full"
                            required
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mb-4">
                        <label
                            for="email"
                            class="block text-900 font-medium mb-2"
                            >Email</label
                        >
                        <InputText
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="w-full"
                            required
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
                            id="password"
                            type="password"
                            v-model="form.password"
                            class="w-full"
                            required
                            autocomplete="new-password"
                        />
                        <InputError
                            class="mt-2 mb-1"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            for="password_confirmation"
                            class="block text-900 font-medium mb-2"
                            >Password</label
                        >
                        <InputText
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            class="w-full"
                            required
                            autocomplete="new-password"
                        />
                        <InputError
                            class="mt-2 mb-1"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div class="mb-5">
                        <div
                            class="flex align-items-center justify-content-between"
                        >
                            <div></div>
                            <Link
                                :href="route('login')"
                                class="font-medium no-underline ml-2 text-blue-500 text-right cursor-pointer"
                            >
                                Already registered?
                            </Link>
                        </div>
                    </div>

                    <Button
                        raised
                        type="submit"
                        :loading="form.processing"
                        label="Register"
                        class="w-full"
                    ></Button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
