<script setup>
import { ref, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import ToggleButton from 'primevue/togglebutton';
import Tooltip from 'primevue/tooltip';

const props = defineProps({
    availableParts: {
        type: Array,
        required: true,
        default: () => []
    },
    selectedParts: {
        type: Object,
        required: true,
        default: () => ({})
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

const emit = defineEmits(['update-quantity', 'view-part']);

const filters = ref({
    'part_number': { value: null, matchMode: 'contains' },
    'description': { value: null, matchMode: 'contains' }
});

const sortByTotalCost = ref(false);

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
    return props.selectedParts[partId] || 0;
};

const calculateTotalCost = (part) => {
    const quantity = getOrderQuantity(part.id);
    return quantity * getCostPerPart(part);
};

const sortedParts = computed(() => {
    if (sortByTotalCost.value) {
        return [...props.availableParts].sort((a, b) => calculateTotalCost(b) - calculateTotalCost(a));
    }
    return props.availableParts;
});

const getPartTooltip = (part) => {
    const products = part.products?.map(p => p.name).join(', ') || 'No associated products';
    const identifiers = part.identifiers?.identifiers?.map(i => `${i.type}: ${i.value}`).join(', ') || 'No identifiers';
    const regulatoryInfo = part.regulatory_information?.identifiers?.map(i => `${i.type}: ${i.value}`).join(', ') || 'No regulatory information';

    return `
        <strong>Associated Products:</strong> ${products}<br>
        <strong>Identifiers:</strong> ${identifiers}<br>
        <strong>Regulatory Information:</strong> ${regulatoryInfo}<br>
        <strong>Manufacturer:</strong> ${part.manufacturer?.name || 'N/A'}<br>
        <strong>Supplier:</strong> ${part.supplier?.name || 'N/A'}
    `;
};
</script>

<template>
    <div class="flex items-center justify-end mb-3">
        <label for="sortByTotalCost" class="mr-2">Sort by Total Cost</label>
        <ToggleButton v-model="sortByTotalCost" inputId="sortByTotalCost" onIcon="pi pi-check" offIcon="pi pi-times" />
    </div>

    <DataTable
        :value="sortedParts"
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
        <template #empty>
            No parts found.
        </template>

        <Column field="part_number" header="Part Number" sortable filterField="part_number">
            <template #filter="{ filterModel, filterCallback }">
                <InputText
                    v-model="filterModel.value"
                    type="text"
                    class="w-full p-inputtext-sm"
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
                    class="w-full p-inputtext-sm"
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

        <Column header="Total Cost" :sortable="sortByTotalCost">
            <template #body="{ data }">
                {{ formatCurrency(calculateTotalCost(data)) }}
            </template>
        </Column>

        <Column header="Actions" :exportable="false">
            <template #body="{ data }">
                <Button
                    label="View Part"
                    icon="pi pi-eye"
                    class="p-button-sm"
                    @click="$emit('view-part', data)"
                />
            </template>
        </Column>

        <template #row="{ data }">
            <tr v-tooltip.top.focus="{ value: getPartTooltip(data), escape: false }">
                <td>{{ data.part_number }}</td>
                <td>{{ data.description }}</td>
                <td>{{ formatCurrency(getCostPerPart(data)) }}</td>
                <td><Tag :value="`${data.replenishment_data?.lead_days || settings.defaultLeadDays} days`" /></td>
                <td>
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
                </td>
                <td>{{ formatCurrency(calculateTotalCost(data)) }}</td>
                <td>
                    <Button
                        label="View Part"
                        icon="pi pi-eye"
                        class="p-button-sm"
                        @click="$emit('view-part', data)"
                    />
                </td>
            </tr>
        </template>
    </DataTable>
</template>

<style scoped>
/* Add any necessary styles here */
</style>
