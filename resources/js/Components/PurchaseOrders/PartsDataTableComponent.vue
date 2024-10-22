<script setup>
import { ref, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import ToggleButton from 'primevue/togglebutton';

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
  const selectedPart = props.selectedParts.find(p => p.part_id === partId);
  return selectedPart ? selectedPart.quantity_ordered : 0;
};

const calculateTotalCost = (part) => {
  const selectedPart = props.selectedParts.find(p => p.part_id === part.id);
  return selectedPart ? selectedPart.quantity_ordered * getCostPerPart(part) : 0;
};

const sortedParts = computed(() => {
  if (sortByTotalCost.value) {
    return [...props.availableParts].sort((a, b) => calculateTotalCost(b) - calculateTotalCost(a));
  }
  return props.availableParts;
});

const updateQuantity = (partId, newQuantity) => {
  emit('update-quantity', partId, newQuantity);
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
  >
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
          @update:modelValue="(value) => updateQuantity(data.id, value)"
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
  </DataTable>
</template>
