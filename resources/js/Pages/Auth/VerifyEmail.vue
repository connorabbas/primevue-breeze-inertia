<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Message from 'primevue/message';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent'
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="w-full sm:w-full md:w-[30rem] mb-4">
            <Message
                v-if="verificationLinkSent"
                severity="success"
                :closable="false"
                class="shadow"
            >
                A new verification link has been sent to the email address you
                provided during registration.
            </Message>
        </div>

        <div
            class="bg-surface-0 dark:bg-surface-900 p-6 shadow rounded-lg w-full sm:w-full md:w-[30rem]"
        >
            <div class="mb-6 text-sm text-muted-color">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-6 flex justify-between items-center">
                    <Button
                        raised
                        type="submit"
                        :loading="form.processing"
                        label="Resend Verification Email"
                        severity="contrast"
                    />
                    <Link
                        :href="route('logout')"
                        method="post"
                        class="text-muted-color hover:text-color"
                        >Log Out</Link
                    >
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
