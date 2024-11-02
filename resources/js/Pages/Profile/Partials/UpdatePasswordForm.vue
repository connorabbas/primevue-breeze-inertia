<script setup>
import { useTemplateRef } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const currentPasswordInput = useTemplateRef('current-password-input');
const newPasswordInput = useTemplateRef('new-password-input');

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
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showSuccessToast();
        },
        onError: () => {
            if (form.errors?.password) {
                form.reset('password', 'password_confirmation');
                newPasswordInput.value.$el.focus();
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
    <form @submit.prevent="updatePassword" class="space-y-6">
        <div class="space-y-2">
            <label for="current_password">Current Password</label>
            <InputText
                required
                id="current_password"
                ref="current-password-input"
                type="password"
                v-model="form.current_password"
                class="w-full"
                :invalid="Boolean(form.errors?.current_password)"
                autocomplete="current-password"
            />
            <Message
                v-if="form.errors?.current_password"
                severity="error"
                variant="simple"
                size="small"
            >
                {{ form.errors?.current_password }}
            </Message>
        </div>

        <div class="space-y-2">
            <label for="password">New Password</label>
            <InputText
                required
                id="password"
                ref="new-password-input"
                type="password"
                v-model="form.password"
                class="w-full"
                :invalid="Boolean(form.errors.password)"
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
                required
                id="password_confirmation"
                type="password"
                v-model="form.password_confirmation"
                class="w-full"
                :invalid="Boolean(form.errors.password_confirmation)"
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
