<!-- CreatePurchaseOrder.vue -->
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

const {
    form,
    errors,
    processing,
    availableSuppliers,
    selectedSupplier,
    supplierParts,
    supplierAddresses,
    settings,
    subtotal,
    taxAmount,
    totalCost,
    updatePartQuantity,
    saveDraft,
    submit,
    reset
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
</script>
<template>
    <AuthenticatedAdminLayout :page-title="pageTitle">
        <div class="layout-container">
            <!-- Header/Toolbar -->
            <div class="toolbar-section">
                <PurchaseOrderToolbar
                    :poNumber="poNumber"
                    :date="poDate"
                    :status="poStatus"
                    @update:poNumber="poNumber = $event"
                    @update:date="poDate = $event"
                    @update:status="poStatus = $event"
                />
            </div>

            <!-- Main Content -->
            <div class="content-wrapper">
                <!-- Left Column -->
                <div class="main-section">
                    <div class="card-stack">
                        <!-- Address Selection -->
                        <div class="content-card">
                            <AddressSelectComponent v-model="form.addresses"
                                                  :available-addresses="supplierAddresses"
                                                  :settings="settings" />
                        </div>

                        <!-- Supplier Selection -->
                        <div class="content-card">
                            <SupplierSelectComponent v-model="form.supplier_id"
                                                   :suppliers="availableSuppliers"
                                                   :loading="processing" />
                        </div>

                        <!-- Parts Table -->
                        <div class="content-card table-container">
                            <PartsDataTableComponent
                                v-if="selectedSupplier"
                                :available-parts="supplierParts"
                                :selected-parts="form.parts"
                                :settings="settings"
                                @update-quantity="handleUpdateQuantity"
                                @view-part="handleViewPart"
                            />
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="summary-section">
                    <div class="summary-sticky">
                        <div class="content-card">
                            <OrderSummary :subtotal="subtotal"
                                        :taxRate="form.tax_rate"
                                        :additionalCosts="form.additional_costs"
                                        @update:taxRate="form.tax_rate = $event"
                                        @update:additionalCosts="form.additional_costs = $event" />
                        </div>

                        <div class="content-card">
                            <h2 class="text-lg font-semibold mb-4">Special Instructions</h2>
                            <Textarea v-model="form.special_instructions"
                                    rows="4"
                                    class="w-full" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedAdminLayout>
</template>

<style scoped>
.layout-container {
    @apply max-w-[90vw] mx-auto px-10;
}

.content-wrapper {
    @apply grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-4 mt-10 mx-10 px-10;
}

.card-stack {
    @apply space-y-6;
}

.content-card {
    @apply bg-white rounded-lg shadow-sm border border-indigo-100 p-6;
}

.table-container {
    @apply overflow-hidden;
    max-width: calc(100vw - 460px); /* Account for sidebar and padding */
}

.summary-section {
    @apply w-full lg:w-[400px];
}

.summary-sticky {
    @apply sticky top-[140px] space-y-6;
    height: fit-content;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .content-wrapper {
        @apply grid-cols-1;
    }

    .table-container {
        max-width: 100%;
    }

    .summary-sticky {
        @apply relative top-0;
    }
}

/* DataTable specific styling */
:deep(.p-datatable-wrapper) {
    @apply overflow-x-auto;
}

:deep(.p-datatable) {
    min-width: 800px; /* Minimum width before horizontal scroll */
}

:deep(.p-paginator) {
    @apply sticky left-0 right-0 bottom-0;
    background: white;
}
</style>
