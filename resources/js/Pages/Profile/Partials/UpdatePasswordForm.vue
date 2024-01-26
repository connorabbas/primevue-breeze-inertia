<script setup>
import InputError from "@/Components/InputError.vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("user-password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-5">
            <h2 class="text-lg font-medium mt-0">Update Password</h2>

            <p class="mt-1 text-sm text-color-secondary">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword">
            <div class="mb-4">
                <label
                    for="current_password"
                    class="block mb-2"
                    >Current Password</label
                >
                <InputText
                    required
                    id="current_password"
                    ref="currentPasswordInput"
                    type="password"
                    v-model="form.current_password"
                    class="w-full"
                    autocomplete="current-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.current_password"
                />
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2"
                    >New Password</label
                >
                <InputText
                    required
                    id="password"
                    ref="passwordInput"
                    type="password"
                    v-model="form.password"
                    class="w-full"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mb-4">
                <label
                    for="password_confirmation"
                    class="block mb-2"
                    >Confirm Password</label
                >
                <InputText
                    required
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    class="w-full"
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
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
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
