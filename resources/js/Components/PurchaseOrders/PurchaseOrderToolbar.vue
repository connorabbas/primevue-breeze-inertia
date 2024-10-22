<script setup>
import { ref } from 'vue';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/calendar';
import Select from 'primevue/select';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';

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

const updateStatus = (value) => {
    emit('update:status', value);
};
</script>

<template>
    <div class="order-info">
        <!-- PO Number -->
        <div class="p-field">
            <label>PO Number</label>
            <InputGroup>
                <InputGroupAddon>#</InputGroupAddon>
                <InputText :value="poNumber" placeholder="PO Number" @input="updatePoNumber" />
            </InputGroup>
        </div>

        <!-- Order Date -->
        <div class="p-field">
            <label>Order Date</label>
            <DatePicker
                :model-value="date"
                @update:model-value="updateDate"
                :showIcon="true"
                class="w-full"
            />
        </div>

        <!-- Status -->
        <div class="p-field">
            <label>Status</label>
            <Select
                :model-value="status"
                :options="statusOptions"
                optionLabel="label"
                optionValue="value"
                class="w-full"
                @change="updateStatus"
                placeholder="Select Status"
            />
        </div>
    </div>
</template>

<style scoped>
.order-info {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.p-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.p-field label {
    font-weight: 500;
    color: #666;
}

:deep(.p-inputgroup) {
    flex: 1;
}

:deep(.p-calendar),
:deep(.p-dropdown) {
    width: 100%;
}
</style>
