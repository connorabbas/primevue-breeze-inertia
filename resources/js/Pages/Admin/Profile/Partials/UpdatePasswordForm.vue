<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import InputError from '@/Components/InputError.vue';

const currentPasswordInput = ref(null);
const passwordInput = ref(null);

const toast = useToast();
const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showSuccessToast = () => {
    toast.add({
        severity: 'success',
        summary: 'Saved',
        detail: 'Your password has been updated',
        life: 3000,
    });
};
const updatePassword = () => {
    form.put(route('admin.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showSuccessToast();
        },
        onError: () => {
            if (form.errors?.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.$el.focus();
            }
            if (form.errors?.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.$el.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium mt-0 mb-2">Update Password</h2>
            <p class="mb-0 text-sm text-muted-color">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <label for="current_password" class="block mb-2"
                    >Current Password</label
                >
                <InputText
                    required
                    id="current_password"
                    ref="currentPasswordInput"
                    type="password"
                    v-model="form.current_password"
                    class="w-full"
                    :invalid="Boolean(form.errors.current_password)"
                    autocomplete="current-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors?.current_password"
                />
            </div>

            <div>
                <label for="password" class="block mb-2">New Password</label>
                <InputText
                    required
                    id="password"
                    ref="passwordInput"
                    type="password"
                    v-model="form.password"
                    class="w-full"
                    :invalid="Boolean(form.errors.password)"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors?.password" />
            </div>

            <div>
                <label for="password_confirmation" class="block mb-2"
                    >Confirm Password</label
                >
                <InputText
                    required
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    class="w-full"
                    :invalid="Boolean(form.errors.password_confirmation)"
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors?.password_confirmation"
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
                        class="text-sm text-muted-color"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
