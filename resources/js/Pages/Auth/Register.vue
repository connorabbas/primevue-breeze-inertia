<script setup>
import { ref, onMounted } from 'vue';
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

const nameInput = ref(null);

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

onMounted(() => {
    nameInput.value.$el.focus();
});
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div
            class="surface-card p-4 shadow-1 border-round-lg w-full sm:w-12 md:w-30rem"
        >
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="block mb-2">Name</label>
                    <InputText
                        ref="nameInput"
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
                    <label for="email" class="block mb-2">Email</label>
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
                    <label for="password" class="block mb-2">Password</label>
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
                    <label for="password_confirmation" class="block mb-2"
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

                <div class="flex justify-content-end align-items-center">
                    <Link
                        :href="route('login')"
                        class="mr-3 text-color-secondary hover:text-color"
                    >
                        Already registered?
                    </Link>
                    <Button
                        raised
                        type="submit"
                        :loading="form.processing"
                        label="Register"
                        severity="contrast"
                    />
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
