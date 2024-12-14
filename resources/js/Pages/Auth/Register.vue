<script setup>
import { useTemplateRef, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

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

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <label for="name">Name</label>
                <InputText
                    ref="name-input"
                    id="name"
                    type="text"
                    v-model="form.name"
                    fluid
                    :invalid="Boolean(form.errors.name)"
                    required
                    autocomplete="name"
                />
                <Message
                    v-if="form.errors?.name"
                    severity="error"
                    variant="simple"
                    size="small"
                >
                    {{ form.errors?.name }}
                </Message>
            </div>

            <div class="space-y-2">
                <label for="email">Email</label>
                <InputText
                    id="email"
                    type="email"
                    v-model="form.email"
                    fluid
                    :invalid="Boolean(form.errors.email)"
                    required
                    autocomplete="username"
                />
                <Message
                    v-if="form.errors?.email"
                    severity="error"
                    variant="simple"
                    size="small"
                >
                    {{ form.errors?.email }}
                </Message>
            </div>

            <div class="space-y-2">
                <label for="password">Password</label>
                <InputText
                    id="password"
                    type="password"
                    v-model="form.password"
                    fluid
                    :invalid="Boolean(form.errors.password)"
                    required
                    autocomplete="new-password"
                />
                <Message
                    v-if="form.errors?.password"
                    severity="error"
                    variant="simple"
                    size="small"
                >
                    {{ form.errors?.password }}
                </Message>
            </div>

            <div class="space-y-2">
                <label for="password_confirmation">Confirm Password</label>
                <InputText
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    fluid
                    :invalid="Boolean(form.errors.password_confirmation)"
                    required
                    autocomplete="new-password"
                />
                <Message
                    v-if="form.errors?.password_confirmation"
                    severity="error"
                    variant="simple"
                    size="small"
                >
                    {{ form.errors?.password_confirmation }}
                </Message>
            </div>

            <div class="flex justify-end items-center pt-2">
                <Link
                    :href="route('login')"
                    class="mr-4 underline text-muted-color hover:text-color"
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
