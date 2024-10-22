<script setup>
import { ref, computed } from 'vue';
import { usePurchaseOrderForm } from '@/Composables/usePurchaseOrderForm';
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PurchaseOrderHeader from '@/Components/PurchaseOrders/PurchaseOrderHeader.vue';
import SupplierSelectComponent from '@/Components/PurchaseOrders/SupplierSelectComponent.vue';
import PartsDataTableComponent from '@/Components/PurchaseOrders/PartsDataTableComponent.vue';
import OrderSummary from '@/Components/PurchaseOrders/OrderSummary.vue';
import AddressSelectComponent from '@/Components/PurchaseOrders/AddressSelectComponent.vue';
import InputText from 'primevue/inputtext';

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
    // Implement view part functionality
    console.log('Viewing part:', part);
}
</script>

<template>
    <AuthenticatedAdminLayout :page-title="pageTitle">
        <div class="p-4">
            <PurchaseOrderHeader
                :poNumber="poNumber"
                :date="poDate"
                :status="poStatus"
                @update:poNumber="poNumber = $event"
                @update:date="poDate = $event"
                @update:status="poStatus = $event"
            />

            <div class="grid">
                <div class="col-12 md:col-8">
                    <!-- Supplier Selection -->
                    <div class="mb-4">
                        <h2 class="mb-2 text-lg font-bold">Select Supplier</h2>
                        <SupplierSelectComponent
                            v-model="form.supplier_id"
                            :suppliers="availableSuppliers"
                            :loading="processing"
                        />
                    </div>

                    <!-- Parts DataTable -->
                    <div v-if="selectedSupplier" class="mb-4">
                        <h2 class="mb-2 text-lg font-bold">Select Parts</h2>
                        <PartsDataTableComponent
                            :available-parts="supplierParts"
                            :selected-parts="form.parts"
                            :settings="settings"
                            @update-quantity="handleUpdateQuantity"
                            @view-part="handleViewPart"
                        />
                    </div>

                    <!-- Special Instructions -->
                    <div class="mb-4">
                        <h2 class="mb-2 text-lg font-bold">Special Instructions</h2>
                        <InputText
                            v-model="form.special_instructions"
                            type="textarea"
                            rows="4"
                            class="w-full"
                        />
                    </div>
                </div>

                <div class="col-12 md:col-4">
                    <!-- Order Summary -->
                    <OrderSummary
                        :subtotal="subtotal"
                        :taxRate="form.tax_rate"
                        :additionalCosts="form.additional_costs"
                        @update:taxRate="form.tax_rate = $event"
                        @update:additionalCosts="form.additional_costs = $event"
                        @saveDraft="saveDraft"
                        @submit="submit"
                    />
                </div>
            </div>

            <!-- Address Selection -->
            <div class="mt-4">
                <h2 class="mb-2 text-lg font-bold">Addresses</h2>
                <AddressSelectComponent
                    v-model="form.addresses"
                    :available-addresses="supplierAddresses"
                    :settings="settings"
                />
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
