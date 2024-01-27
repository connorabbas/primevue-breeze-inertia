<script setup>
import InputError from "@/Components/InputError.vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const passwordInput = ref(null);
const modalOpen = ref(false);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    modalOpen.value = true;
    //nextTick(() => passwordInput.value.focus()); // not working for some reason
};
const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
const closeModal = () => {
    modalOpen.value = false;
    form.reset();
};
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
            <div class="mb-4">
                <p class="m-0 text-color-secondary">
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
                    @click="closeModal"
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

        <header class="mb-5 flex">
            <div class="w-12 lg:w-10 xl:w-6">
                <h2 class="text-lg font-medium mt-0">Delete Account</h2>
                <p class="mb-0 text-sm text-color-secondary">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Before deleting your account,
                    please download any data or information that you wish to
                    retain.
                </p>
            </div>
        </header>

        <Button
            raised
            @click="confirmUserDeletion"
            label="Delete Account"
            severity="danger"
        />
    </section>
</template>
