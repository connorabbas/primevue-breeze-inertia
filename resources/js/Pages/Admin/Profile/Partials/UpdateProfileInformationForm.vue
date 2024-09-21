<script setup>
import { useTemplateRef, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import Message from 'primevue/message';
import InputError from '@/Components/InputError.vue';

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
    form.patch(route('admin.profile.update'), {
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
    <section>
        <header>
            <h2 class="text-lg font-medium mt-0 mb-2">Profile Information</h2>
            <p class="mb-0 text-sm text-muted-color">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
            <div>
                <label for="name" class="block mb-2">Name</label>
                <InputText
                    required
                    ref="name-input"
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="w-full"
                    :invalid="Boolean(form.errors.name)"
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors?.name" />
            </div>
            <div>
                <label for="email" class="block mb-2">Email</label>
                <InputText
                    required
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="w-full"
                    :invalid="Boolean(form.errors.email)"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors?.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        class="underline text-sm text-muted-color underline text-muted-color hover:text-color"
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
    </section>
</template>
