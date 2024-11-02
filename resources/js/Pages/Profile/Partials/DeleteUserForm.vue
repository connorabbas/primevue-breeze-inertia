<script setup>
import { ref, useTemplateRef } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

const passwordInput = useTemplateRef('password-input');
const modalOpen = ref(false);

const form = useForm({
    password: '',
});

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => (modalOpen.value = false),
        onError: () => passwordInput.value.$el.focus(),
        onFinish: () => form.reset(),
    });
};

function focusPasswordInput() {
    passwordInput.value.$el.focus();
}
</script>

<template>
    <section>
        <Dialog
            :draggable="false"
            position="center"
            v-model:visible="modalOpen"
            modal
            header="Are you sure you want to delete your account?"
            :style="{ width: '40rem' }"
            @show="focusPasswordInput"
        >
            <div class="mb-6">
                <p class="m-0 text-muted-color">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>
            </div>

            <div>
                <InputText
                    autofocus
                    required
                    id="password"
                    ref="password-input"
                    type="password"
                    placeholder="Password"
                    v-model="form.password"
                    class="w-full"
                    :invalid="Boolean(form.errors.password)"
                    autocomplete="current-password"
                    @keyup.enter="deleteUser"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <template #footer>
                <Button
                    class="mr-2"
                    label="Cancel"
                    plain
                    text
                    @click="modalOpen = false"
                />
                <Button
                    raised
                    @click="deleteUser"
                    :loading="form.processing"
                    label="Delete Account"
                    severity="danger"
                />
            </template>
        </Dialog>

        <Button
            raised
            @click="modalOpen = true"
            label="Delete Account"
            severity="danger"
        />
    </section>
</template>
