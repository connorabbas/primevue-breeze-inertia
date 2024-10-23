<script setup>
import { ref, computed } from 'vue';
import { usePurchaseOrderForm } from '@/Composables/usePurchaseOrderForm';
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import SupplierSelectComponent from '@/Components/PurchaseOrders/SupplierSelectComponent.vue';
import PartsDataTableComponent from '@/Components/PurchaseOrders/PartsDataTableComponent.vue';
import OrderSummary from '@/Components/PurchaseOrders/OrderSummary.vue';
import AddressSelectComponent from '@/Components/PurchaseOrders/AddressSelectComponent.vue';
import PurchaseOrderToolbar from '@/Components/PurchaseOrders/PurchaseOrderToolbar.vue';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

const pageTitle = '';

const props = defineProps({
    initialData: {
        type: Object,
        required: true
    }
});

// Destructure all required properties and methods from the composable
const {
    form,
    errors,
    processing,
    selectedSupplier,
    supplierParts,
    supplierAddresses,
    settings,
    subtotal,
    taxAmount,
    totalCost,
    isValid,
    updatePartQuantity,
    saveDraft,
    submit,
    reset,
    availableSuppliers // Make sure to destructure this
} = usePurchaseOrderForm(props.initialData);

const poNumber = ref('PO-' + Date.now());
const poDate = ref(new Date());
const poStatus = ref('Draft');

function handleUpdateQuantity(partId, quantity) {
    updatePartQuantity(partId, quantity);
}

function handleViewPart(part) {
    console.log('Viewing part:', part);
}

function handleSubmit() {
    form.value.number = poNumber.value;
    form.value.date = poDate.value;
    form.value.status = poStatus.value;
    submit();
}

function handleSaveDraft() {
    form.value.number = poNumber.value;
    form.value.date = poDate.value;
    form.value.status = poStatus.value;
    saveDraft();
}

function handleReset() {
    poNumber.value = 'PO-' + Date.now();
    poDate.value = new Date();
    poStatus.value = 'Draft';
    reset();
}
</script>

<template>
  <AuthenticatedAdminLayout :page-title="pageTitle">
    <div class="layout-container">
      <!-- Fixed-height header toolbar -->
      <div class="toolbar-section">
        <PurchaseOrderToolbar
          :poNumber="poNumber"
          :date="poDate"
          :status="poStatus"
          :isValid="isValid"
          :processing="processing"
          @update:poNumber="poNumber = $event"
          @update:date="poDate = $event"
          @update:status="poStatus = $event"
          @submit="handleSubmit"
          @save-draft="handleSaveDraft"
          @reset="handleReset"
        />
      </div>

      <!-- Main content with fixed height containers -->
      <div class="content-layout">
        <!-- Left column - fixed width and scrollable -->
        <div class="main-content">
          <div class="card-stack space-y-4">
            <!-- Address section -->
            <div class="content-card">
              <AddressSelectComponent
                v-model="form.addresses"
                :available-addresses="supplierAddresses"
                :settings="settings"
              />
            </div>

            <!-- Supplier section -->
            <div class="content-card">
              <SupplierSelectComponent
                v-model="form.supplier_id"
                :suppliers="availableSuppliers"
                :loading="processing"
              />
            </div>

            <!-- Parts table -->
            <div class="content-card">
              <div class="h-full flex flex-col">
                <PartsDataTableComponent
                  v-if="selectedSupplier"
                  :available-parts="supplierParts"
                  :selected-parts="form.parts"
                  :settings="settings"
                  @update-quantity="handleUpdateQuantity"
                  @view-part="handleViewPart"
                />
                <!-- Placeholder when no supplier selected -->
                <div v-else class="flex-grow flex items-center justify-center text-gray-500">
                  Select a supplier to view available parts
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right column - sticky sidebar -->
        <div class="summary-section">
          <div class="summary-sticky">
            <!-- Order summary card -->
            <div class="content-card">
              <OrderSummary
                :subtotal="subtotal"
                :taxRate="form.tax_rate"
                :additionalCosts="form.additional_costs"
                @update:taxRate="form.tax_rate = $event"
                @update:additionalCosts="form.additional_costs = $event"
              />
            </div>

            <!-- Special instructions card -->
            <div class="content-card mt-4">
              <h2 class="text-lg font-semibold mb-4">Special Instructions</h2>
              <Textarea
                v-model="form.special_instructions"
                rows="4"
                class="w-full"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedAdminLayout>
</template>

<style scoped>
.layout-container {
  @apply max-w-[1800px] mx-auto px-4;
}

.content-layout {
  @apply grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-4 mt-6;
}

.main-content {
  @apply min-w-0; /* prevents flex child overflow */
}

.summary-section {
  @apply w-full lg:w-[400px];
}

.summary-sticky {
  @apply sticky top-[140px]; /* Adjust this value based on your header + toolbar height */
  height: fit-content;
}

.content-card {
  @apply bg-white rounded-lg shadow-sm border border-indigo-100 p-6;
}

/* DataTable styles */
:deep(.p-datatable-wrapper) {
  @apply overflow-x-auto;
}

:deep(.p-datatable) {
  min-width: 800px;
}

/* Mobile responsiveness */
@media (max-width: 1024px) {
  .content-layout {
    @apply grid-cols-1;
  }

  .summary-sticky {
    @apply relative top-0;
  }

  .summary-section {
    @apply w-full;
  }
}
</style>
