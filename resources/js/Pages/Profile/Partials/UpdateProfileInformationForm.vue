<script setup>
import { useTemplateRef, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const nameInput = useTemplateRef('name-input');

const user = usePage().props.auth.user;
const toast = useToast();
const form = useForm({
    name: user.name,
    email: user.email,
});

const showSuccessToast = () => {
    toast.add({
        severity: 'success',
        summary: 'Saved',
        detail: 'Profile information has been updated',
        life: 3000,
    });
};
const updateProfileInformation = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            showSuccessToast();
        },
    });
};

onMounted(() => {
    nameInput.value.$el.focus();
});
</script>

<template>
    <form @submit.prevent="updateProfileInformation" class="space-y-6">
        <div class="space-y-2">
            <label for="name">Name</label>
            <InputText
                required
                ref="name-input"
                id="name"
                type="text"
                v-model="form.name"
                fluid
                :invalid="Boolean(form.errors.name)"
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
                required
                id="email"
                type="email"
                v-model="form.email"
                fluid
                :invalid="Boolean(form.errors.email)"
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

        <div v-if="mustVerifyEmail && user.email_verified_at === null">
            <p class="text-sm mt-2">
                Your email address is unverified.
                <Link
                    :href="route('verification.send')"
                    method="post"
                    class="underline text-sm text-muted-color hover:text-color"
                >
                    Click here to re-send the verification email.
                </Link>
            </p>

            <Message
                v-if="status === 'verification-link-sent'"
                severity="success"
                :closable="false"
                class="shadow mt-4"
            >
                A new verification link has been sent to your email address.
            </Message>
        </div>

        <div class="flex items-center gap-4">
            <Button
                raised
                type="submit"
                :loading="form.processing"
                label="Save"
                severity="contrast"
            />

            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p
                    v-if="form.recentlySuccessful"
                    class="text-sm text-muted-color"
                >
                    Saved.
                </p>
            </Transition>
        </div>
    </form>
</template>
