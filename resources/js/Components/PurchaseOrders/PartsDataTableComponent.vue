<script setup>
import { ref, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';

const props = defineProps({
    availableParts: {
        type: Array,
        required: true,
        default: () => []
    },
    selectedParts: {
        type: Array,
        required: true,
        default: () => []
    },
    settings: {
        type: Object,
        required: true,
        default: () => ({
            minQuantity: 1,
            defaultLeadDays: 1
        })
    }
});

const emit = defineEmits(['add-part', 'update-quantity', 'remove-part']);

const filters = ref({
    'part_number': { value: null, matchMode: 'contains' },
    'description': { value: null, matchMode: 'contains' }
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value || 0);
};

const getCostPerPart = (part) => {
    return part.replenishment_data?.purchaseTerms?.[0]?.cost_per_part || 0;
};

const getOrderQuantity = (partId) => {
    const selectedPart = props.selectedParts.find(part => part.part_id === partId);
    return selectedPart ? selectedPart.quantity_ordered : 0;
};

const calculateTotalCost = (part) => {
    const quantity = getOrderQuantity(part.id);
    return quantity * getCostPerPart(part);
};
</script>

<template>
    <DataTable
        :value="availableParts"
        :filters="filters"
        filterDisplay="row"
        dataKey="id"
        :paginator="true"
        :rows="10"
        :rowsPerPageOptions="[10, 25, 50]"
        responsiveLayout="scroll"
        class="p-datatable-sm"
        removableSort
        :scrollable="true"
        scrollHeight="400px"
        :loading="loading"
    >
        <Column field="part_number" header="Part Number" sortable filterField="part_number">
            <template #filter="{ filterModel, filterCallback }">
                <InputText
                    v-model="filterModel.value"
                    type="text"
                    class="p-inputtext-sm w-full"
                    @input="filterCallback"
                    placeholder="Search part number"
                />
            </template>
        </Column>

        <Column field="description" header="Description" sortable filterField="description">
            <template #filter="{ filterModel, filterCallback }">
                <InputText
                    v-model="filterModel.value"
                    type="text"
                    class="p-inputtext-sm w-full"
                    @input="filterCallback"
                    placeholder="Search description"
                />
            </template>
        </Column>

        <Column field="unit_cost" header="Unit Cost" sortable>
            <template #body="{ data }">
                {{ formatCurrency(getCostPerPart(data)) }}
            </template>
        </Column>

        <Column field="lead_days" header="Lead Time">
            <template #body="{ data }">
                <Tag :value="`${data.replenishment_data?.lead_days || settings.defaultLeadDays} days`" />
            </template>
        </Column>

        <Column header="Order Quantity">
            <template #body="{ data }">
                <InputNumber
                    :modelValue="getOrderQuantity(data.id)"
                    :min="settings.minQuantity"
                    :step="1"
                    :showButtons="true"
                    buttonLayout="horizontal"
                    decrementButtonClass="p-button-secondary"
                    incrementButtonClass="p-button-secondary"
                    incrementButtonIcon="pi pi-plus"
                    decrementButtonIcon="pi pi-minus"
                    @update:modelValue="(value) => $emit('update-quantity', data.id, value)"
                />
            </template>
        </Column>

        <Column header="Total Cost">
            <template #body="{ data }">
                {{ formatCurrency(calculateTotalCost(data)) }}
            </template>
        </Column>

        <Column header="Actions" :exportable="false">
            <template #body="{ data }">
                <div class="flex gap-2">
                    <Button
                        v-if="!getOrderQuantity(data.id)"
                        icon="pi pi-plus"
                        severity="success"
                        text
                        rounded
                        @click="$emit('add-part', data)"
                        v-tooltip.top="'Add Part'"
                    />
                    <Button
                        v-else
                        icon="pi pi-trash"
                        severity="danger"
                        text
                        rounded
                        @click="$emit('remove-part', data.id)"
                        v-tooltip.top="'Remove Part'"
                    />
                </div>
            </template>
        </Column>
    </DataTable>
</template>
