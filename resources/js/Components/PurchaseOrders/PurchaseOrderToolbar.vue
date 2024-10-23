<script setup>
import { ref } from 'vue';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Button from 'primevue/button';
import Menubar from 'primevue/menubar';
import IftaLabel from 'primevue/iftalabel';
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

const items = ref([
    {
        label: 'Dashboard',
        icon: 'pi pi-chart-line'
    },
    {
        label: 'Purchase Orders',
        icon: 'pi pi-shopping-cart'
    },
    {
        label: 'Suppliers',
        icon: 'pi pi-users'
    }
]);
</script>

<template>

 <nav class="po-toolbar block w-11/12 mx-auto px-4 py-2 text-black border border-indigo-100 shadow-md rounded-md lg:px-10 lg:py-3">
        <div class="container flex flex-wrap items-center justify-between mx-auto text-gray-500">
        <ul class="flex flex-row gap-2">
    <li class="flex items-center p-1 text-sm w-full">
        <IftaLabel class="w-full">
            <InputText
                class="w-full"
                :value="poNumber"
                @input="updatePoNumber"
                inputId="purchaseOrderNumber"
            />
            <label for="purchaseOrderNumber">PO#</label>
        </IftaLabel>
    </li>

    <li class="flex items-center p-1 text-sm w-full">
        <IftaLabel class="w-full">
            <DatePicker
                class="w-full"
                :model-value="date"
                @update:model-value="updateDate"
                inputId="date"
                showIcon
                iconDisplay="input"
                variant="filled"
            />
            <label for="date">Date</label>
        </IftaLabel>
    </li>
</ul>
    <div class=" lg:block">

      <ul class="flex flex-col justify-end gap-2 mt-2 mb-4 lg:mb-0 lg:mt-0 lg:flex-row lg:items-center lg:gap-6">
        <li class="flex items-center p-1 text-sm gap-x-2">
            <Button
                        icon="pi pi-save"
                        label="Save Draft"
                        severity="secondary"
                        @click="$emit('save-draft')"
                    />  <Button
                        icon="pi pi-chevron-right"
                        label="Submit"
                        severity="success"
                        @click="$emit('submit')"
                    />
                    <Button
                        icon="pi pi-times"
                        label="Cancel"
                        severity="danger"
                        @click="$emit('reset')"
                    />
            <!--<Select
                    :model-value="status"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select Status"
                    class="w-full md:w-14rem"
                    @change="updateStatus"
                />-->
        </li>
      </ul>
    </div>
  </div>
</nav>
</template>

<style scoped>
.toolbar-wrapper {
    border-bottom: 1px solid var(--surface-border);
}

.logo-container {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-container span {
    color: white;
}

:deep(.p-menubar) {
    background: transparent;
    padding: 0.5rem 1rem;
}

:deep(.p-menubar-root-list) {
    margin-left: 2rem;
}

:deep(.p-menuitem-link) {
    padding: 0.75rem 1.25rem;
}

:deep(.p-menuitem-icon) {
    margin-right: 0.5rem;
}

:deep(.p-inputgroup) {
    width: 200px;
}

:deep(.p-datepicker) {
    width: 200px;
}

.toolbar-content {
    border-top: 1px solid var(--surface-border);
    background: var(--surface-ground);
}

:deep(.p-button.p-button-text) {
    color: var(--surface-600);
}

:deep(.p-button.p-button-text:hover) {
    background: var(--surface-100);
}
</style>
