<script setup>
import { ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "../../Components/InputLabel.vue";
import TextInput from "../../Components/TextInput.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Checkbox from "../../Components/Checkbox.vue";
import { Head, useForm } from "@inertiajs/vue3";

const roles = ref([]);
const permissions = ref([]);

const roleForm = useForm({ name: "" });
const permissionForm = useForm({ name: "" });
const assignForm = useForm({ roleId: "", permissions: [] });

const fetchRolesAndPermissions = async () => {};
const createRole = () => {};
const createPermission = () => {};
const assignPermissions = () => {};

onMounted(() => {
    fetchRolesAndPermissions();
});
</script>

<template>
    <Head title="Roles & Permissions" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Roles & Permissions</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
            </div>
        </div>
    </AdminLayout>
</template>
