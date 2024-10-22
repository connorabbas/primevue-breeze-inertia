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

const pageTitle = 'Create Purchase Order';

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
        <div class="po-layout">
            <!-- Header Section -->
            <div class="header-section">
                <div class="panel">
                    <div class="order-info">
                        <PurchaseOrderToolbar
                            :poNumber="poNumber"
                            :date="poDate"
                            :status="poStatus"
                            @update:poNumber="poNumber = $event"
                            @update:date="poDate = $event"
                            @update:status="poStatus = $event"
                        />
                    </div>
                </div>
                <div class="panel">
                    <div class="action-buttons">
                        <button class="p-button p-button-secondary" @click="saveDraft">
                            <i class="pi pi-save"></i>
                            <span>Save Draft</span>
                        </button>
                        <button class="p-button p-button-success" @click="submit">
                            <i class="pi pi-check"></i>
                            <span>Submit PO</span>
                        </button>
                        <button class="p-button p-button-danger" @click="reset">
                            <i class="pi pi-times"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="content-grid">
                <!-- Main Content Area -->
                <div class="main-content">
                    <!-- Supplier Selection -->
                    <div class="mb-4 panel">
                        <h2 class="mb-2 text-lg font-bold">Select Supplier</h2>
                        <SupplierSelectComponent
                            v-model="form.supplier_id"
                            :suppliers="availableSuppliers"
                            :loading="processing"
                        />
                    </div>

                    <!-- Parts Table -->
                    <div class="panel">
                        <h2 class="mb-2 text-lg font-bold">Select Parts</h2>
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

                <!-- Right Side Panel -->
                <div class="side-panel">
                    <!-- Order Summary -->
                    <div class="mb-4 panel">
                        <OrderSummary
                            :subtotal="subtotal"
                            :taxRate="form.tax_rate"
                            :additionalCosts="form.additional_costs"
                            @update:taxRate="form.tax_rate = $event"
                            @update:additionalCosts="form.additional_costs = $event"
                        />
                    </div>

                    <!-- Addresses -->
                    <div class="mb-4 panel">
                        <h2 class="mb-2 text-lg font-bold">Addresses</h2>
                        <AddressSelectComponent
                            v-model="form.addresses"
                            :available-addresses="supplierAddresses"
                            :settings="settings"
                        />
                    </div>

                    <!-- Special Instructions -->
                    <div class="panel">
                        <h2 class="mb-2 text-lg font-bold">Special Instructions</h2>
                        <Textarea
                            v-model="form.special_instructions"
                            rows="4"
                            class="w-full"
                            placeholder="Enter any special instructions..."
                        />
                    </div>
                </div>
            </div>

            <!-- Error Display -->
            <div v-if="Object.keys(errors).length > 0" class="p-4 mt-4 border border-red-200 rounded-lg bg-red-50">
                <h2 class="mb-2 text-lg font-semibold text-red-700">Errors</h2>
                <ul class="text-red-600 list-disc list-inside">
                    <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                </ul>
            </div>
        </div>
    </AuthenticatedAdminLayout>
</template>

<style scoped>
.po-layout {
    padding: 1rem;
    display: grid;
    gap: 1rem;
    background: #f8f9fa;
}

/* Header Section */
.header-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* Main Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 1rem;
}

.panel {
    background: white;
    border-radius: 6px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .header-section {
        grid-template-columns: 1fr;
    }
}
</style>
