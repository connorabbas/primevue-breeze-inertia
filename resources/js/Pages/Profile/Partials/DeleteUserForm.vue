<script setup>
import { ref, watch, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import InputError from '@/Components/InputError.vue';

const passwordInput = ref(null);
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

watch(modalOpen, (newModalOpen) => {
    if (newModalOpen) {
        nextTick(() => {
            passwordInput.value.$el.focus();
        });
    } else {
        form.clearErrors();
    }
});
</script>

<template>
    <section class="space-y-6">
        <Dialog
            position="center"
            v-model:visible="modalOpen"
            modal
            header="Are you sure you want to delete your account?"
            :style="{ width: '40rem' }"
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
                    required
                    id="password"
                    ref="passwordInput"
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

        <header>
            <h2 class="text-lg font-medium mt-0 mb-2">Delete Account</h2>
            <p class="mb-0 text-sm text-muted-color">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <Button
            raised
            @click="modalOpen = true"
            label="Delete Account"
            severity="danger"
        />
    </section>
</template>
