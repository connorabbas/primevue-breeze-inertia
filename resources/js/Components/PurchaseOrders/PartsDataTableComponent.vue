<script setup>
import { ref, computed, watch } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import SupplierSelectComponent from './SupplierSelectComponent.vue';

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
            minQuantity: 0,
            defaultLeadDays: 0
        })
    }
});

const loading = ref(true); // Start with loading state

// Watch for changes in availableParts
watch(() => props.availableParts, (newParts) => {
    loading.value = true;
    // Simulate loading delay - remove this in production
    setTimeout(() => {
        loading.value = false;
    }, 500);
}, { immediate: true });

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
  <div class="parts-container">
    <h2 class="mb-2 text-lg font-bold">Select Parts</h2>
    <div class="table-actions">
      <span class="p-input-icon-right">
        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-search"></i>
          </InputGroupAddon>
          <InputText
            v-model="filters.global.value"
            placeholder="Search Parts"
            class="search-input"
            :disabled="loading"
          />
        </InputGroup>
      </span>
    </div>

    <!-- Table with Loading and Empty States -->
    <div class="datatable-wrapper">
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
        stripedRows="true"
        scrollHeight="400px"
        :loading="loading"
        v-model:filters="filters"
      >
        <!-- Loading Template -->
        <template #loading>
          <div class="flex justify-center items-center p-4">
            <div class="flex flex-col items-center gap-2">
              <i class="pi pi-spin pi-spinner text-2xl"></i>
              <span>Loading parts...</span>
            </div>
          </div>
        </template>

        <!-- Empty Template -->
        <template #empty>
          <div class="flex flex-col items-center justify-center p-6 text-gray-500">
            <i class="pi pi-inbox text-4xl mb-2"></i>
            <span class="text-lg font-medium">No Parts Available</span>
            <p class="text-sm" v-if="filters.global.value">
              No results found for "{{ filters.global.value }}"
            </p>
            <p class="text-sm" v-else>
              Select a supplier to view available parts
            </p>
          </div>
        </template>

        <!-- Skeleton Loading for Rows -->
        <template #loadingbody>
          <tr class="p-datatable-loading-row">
            <td v-for="col in 7" :key="col" class="p-3">
              <div class="animate-pulse bg-slate-200 h-4 rounded"></div>
            </td>
          </tr>
        </template>

        <!-- Regular Columns -->
        <Column field="part_number" header="Part Number" sortable>
          <template #body="{ data }">
            {{ data.part_number }}
          </template>
        </Column>

        <Column field="description" header="Description" class="w-full">
          <template #body="{ data }">
            {{ data.description }}
          </template>
        </Column>

        <Column field="unit_cost" header="Unit Cost" class="w-full" sortable>
          <template #body="{ data }">
            {{ formatCurrency(getCostPerPart(data)) }}
          </template>
        </Column>

        <Column field="lead_days" header="Lead Time" class="w-full">
          <template #body="{ data }">
            <span class="bg-gray-900 text-white text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-800 dark:text-gray-100 text-center block min-w-[60px]">
              {{`${data.replenishment_data?.lead_days || settings.defaultLeadDays} days`}}
            </span>
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
              :disabled="loading"
              @update:modelValue="(value) => updateQuantity(data.id, value)"
            />
          </template>
        </Column>

        <Column header="Total Cost" sortable>
          <template #body="{ data }">
            {{ formatCurrency(calculateTotalCost(data)) }}
          </template>
        </Column>

        <Column header="Actions" style="width: 100px">
          <template #body="{ data }">
            <div class="flex gap-1">
              <Button
                icon="pi pi-eye"
                class="p-button-rounded p-button-info p-button-sm"
                :disabled="loading"
                @click="$emit('view-part', data)"
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger p-button-sm"
                :disabled="loading"
                @click="updateQuantity(data.id, 0)"
              />
            </div>
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>
<style scoped>
.parts-container {
  width: 100%;
  overflow-x: hidden; /* Prevent horizontal overflow */
}

.datatable-wrapper {
  width: 100%;
  overflow-x: auto; /* Allow table to scroll horizontally if needed */
}

.table-actions {
  display: flex;
  justify-content: end;
  align-items: center;
  margin-bottom: 1rem;
}

/* Ensure the table stays within bounds */
:deep(.p-datatable) {
  max-width: 100%;
}

/* Add loading state styles */
:deep(.p-datatable-loading-overlay) {
    background: rgba(255, 255, 255, 0.8);
}

:deep(.p-datatable-loading) {
    background: white;
}

.p-datatable-loading-row td {
    padding: 1rem;
}

/* Ensure proper height for empty state */
:deep(.p-datatable-emptymessage) {
    min-height: 300px;
}

/* Animation for skeleton loading */
@keyframes pulse {
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
:deep(.p-datatable-wrapper) {
  border: 1px solid #e2e8f0;
  max-width: 100%;
}

/* Make columns more compact */
:deep(.p-datatable-thead > tr > th) {
  padding: 0.5rem;
}

:deep(.p-datatable-tbody > tr > td) {
  padding: 0.5rem;
}

/* Make the quantity column more compact */
:deep(.p-inputnumber) {
  width: 130px;
}

.search-input {
  width: 150px;
}
</style>
