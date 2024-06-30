<script setup>
import { ref, onMounted } from "vue";
import InputError from "@/Components/InputError.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import Message from "primevue/message";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const nameInput = ref(null);

const user = usePage().props.auth.user;
const toast = useToast();
const form = useForm({
    name: user.name,
    email: user.email,
});

const showSuccessToast = () => {
    toast.add({
        severity: "success",
        summary: "Saved",
        detail: "Profile information has been updated",
        life: 3000,
    });
};
const updateProfileInformation = () => {
    form.patch(route("profile.update"), {
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
        <header class="mb-8 flex">
            <div class="w-full lg:w-10/12 xl:w-6/12">
                <h2 class="text-lg font-medium mt-0">Profile Information</h2>

                <p class="mb-0 text-sm text-muted-color">
                    Update your account's profile information and email address.
                </p>
            </div>
        </header>

        <Toast />

        <form @submit.prevent="updateProfileInformation">
            <div class="mb-6 flex">
                <div class="w-full lg:w-10/12 xl:w-6/12">
                    <label for="name" class="block mb-2">Name</label>
                    <InputText
                        required
                        ref="nameInput"
                        id="name"
                        type="text"
                        v-model="form.name"
                        class="w-full"
                        :class="form.errors?.name ? 'p-invalid' : ''"
                        autocomplete="name"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors?.name"
                    />
                </div>
            </div>
            <div class="mb-6 flex">
                <div class="w-full lg:w-10/12 xl:w-6/12">
                    <label for="email" class="block mb-2">Email</label>
                    <InputText
                        required
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        :class="form.errors?.email ? 'p-invalid' : ''"
                        autocomplete="username"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors?.email"
                    />
                </div>
            </div>

            <div
                v-if="mustVerifyEmail && user.email_verified_at === null"
                class="mb-6 flex"
            >
                <div class="w-full lg:w-10/12 xl:w-6/12">
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
                        class="shadow-sm"
                    >
                        A new verification link has been sent to your email
                        address.
                    </Message>
                </div>
            </div>

            <div class="flex content-center gap-4">
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
