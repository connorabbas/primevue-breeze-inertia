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

const emit = defineEmits(['update-quantity', 'view-part']);

const filters = ref({
  global: { value: null, matchMode: 'contains' }
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
  const selectedPart = props.selectedParts.find(p => p.part_id === partId);
  return selectedPart ? selectedPart.quantity_ordered : 0;
};

const calculateTotalCost = (part) => {
  const selectedPart = props.selectedParts.find(p => p.part_id === part.id);
  return selectedPart ? selectedPart.quantity_ordered * getCostPerPart(part) : 0;
};

const updateQuantity = (partId, newQuantity) => {
  emit('update-quantity', partId, newQuantity);
};

const filteredParts = computed(() => {
  if (!filters.value.global.value) {
    return props.availableParts;
  }
  return props.availableParts.filter(part =>
    part.part_number.toLowerCase().includes(filters.value.global.value.toLowerCase())
  );
});
</script>

<template>
    <div class="flex justify-end">
        <InputGroup class="w-1/5 mb-2">
    <InputGroupAddon>
        <i class="pi pi-search"></i>
    </InputGroupAddon>
    <InputText v-model="filters.global.value" placeholder="Search Part Number" class="w-full" />
   </InputGroup>
</div>
  <DataTable
    :value="filteredParts"
    dataKey="id"
    :paginator="true"
    :rows="20"
    :rowsPerPageOptions="[20, 50, 100]"
    responsiveLayout="scroll"
    class="p-datatable-sm"
    removableSort
    :scrollable="true"
    scrollHeight="flex"
  >
    <Column field="part_number" header="Part Number" sortable>
      <template #body="{ data }">
        {{ data.part_number }}
      </template>
    </Column>

    <Column field="description" header="Description">
      <template #body="{ data }">
        {{ data.description }}
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

    <Column header="Total Cost" sortable>
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

<style scoped>
.p-datatable {
  height: 100%;
}
</style>
