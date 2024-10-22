<script setup>
import { ref } from 'vue';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
const props = defineProps({
    poNumber: {
        type: String,
        required: true
    },
    date: {
        type: Date,
        required: true
    },
    status: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['update:poNumber', 'update:date', 'update:status']);

const statusOptions = ref([
    { label: 'Draft', value: 'Draft' },
    { label: 'Pending', value: 'Pending' },
    { label: 'Approved', value: 'Approved' },
    { label: 'Completed', value: 'Completed' }
]);

const updatePoNumber = (event) => {
    emit('update:poNumber', event.target.value);
};

const updateDate = (value) => {
    emit('update:date', value);
};

const updateStatus = (event) => {
    emit('update:status', event.value);
};
</script>

<template>
    <Toolbar class="mb-4">
        <template #start>
            <Button icon="pi pi-plus" class="mr-2" severity="secondary" text />
            <Button icon="pi pi-print" class="mr-2" severity="secondary" text />
            <Button icon="pi pi-upload" severity="secondary" text />
        </template>

        <template #center>
            <InputGroup>
            <InputGroupAddon>#</InputGroupAddon>
            <InputNumber  :value="poNumber" placeholder="PO Number" @input="updatePoNumber" />

        </InputGroup>
            <Calendar :model-value="date" class="ml-2" @update:model-value="updateDate" />
            <Select :model-value="status" :options="statusOptions" optionLabel="label" class="ml-2" @change="updateStatus" />
        </template>

        <template #end>
            <Button label="Save" icon="pi pi-check" class="mr-2" />
            <Button label="Cancel" icon="pi pi-times" severity="secondary" />
        </template>
    </Toolbar>
</template>

<style scoped>
.p-toolbar {
    border-radius: 6px;
    padding: 1rem;
}
</style>
