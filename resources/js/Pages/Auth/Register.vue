<script setup>
import { useTemplateRef, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const nameInput = useTemplateRef('name-input');

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => {
    nameInput.value.$el.focus();
});
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div class="mb-6">
                <label for="name" class="block mb-2">Name</label>
                <InputText
                    ref="name-input"
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="w-full"
                    :invalid="Boolean(form.errors.name)"
                    required
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mb-6">
                <label for="email" class="block mb-2">Email</label>
                <InputText
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
                    >Confirm Password</label
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
                <Link
                    :href="route('login')"
                    class="mr-4 text-muted-color underline text-muted-color hover:text-color"
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
    </GuestLayout>
</template>
