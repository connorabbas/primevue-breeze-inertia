<script setup>
import { ref, computed } from 'vue';
import { usePurchaseOrderForm } from '@/Composables/usePurchaseOrderForm';
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';
import Button from 'primevue/button';
import SupplierSelectComponent from '@/Components/PurchaseOrders/SupplierSelectComponent.vue';
import PartsDataTableComponent from '@/Components/PurchaseOrders/PartsDataTableComponent.vue';
import AddressSelectComponent from '@/Components/PurchaseOrders/AddressSelectComponent.vue';

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
    addPart,
    removePart,
    updatePartQuantity,
    saveDraft,
    submit,
    reset
} = usePurchaseOrderForm(props.initialData);

const selectedParts = computed(() => {
    const partsObject = {};
    form.value.parts.forEach(part => {
        partsObject[part.part_id] = part.quantity_ordered;
    });
    return partsObject;
});

function handleUpdateQuantity(partId, quantity) {
    updatePartQuantity(partId, quantity);
}

function handleViewPart(part) {
    // Implement view part functionality
    console.log('Viewing part:', part);
}
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedAdminLayout :page-title="pageTitle">
        <Container :spaced-mobile="false">
            <ResponsiveCard>
                <!-- Supplier Selection -->
                <div class="mb-6">
                    <h2 class="mb-2 text-lg font-bold">Select Supplier</h2>
                    <SupplierSelectComponent
                        v-model="form.supplier_id"
                        :suppliers="availableSuppliers"
                        :loading="processing"
                    />
                </div>

                <!-- Parts Selection -->
                <div v-if="selectedSupplier" class="mb-6">
                    <h2 class="mb-2 text-lg font-bold">Select Parts</h2>
                    <PartsDataTableComponent
                        :available-parts="supplierParts"
                        :selected-parts="selectedParts"
                        :settings="settings"
                        @update-quantity="handleUpdateQuantity"
                        @view-part="handleViewPart"
                    />
                </div>

                <!-- Address Selection -->
                <div v-if="selectedSupplier" class="mb-6">
                    <h2 class="mb-2 text-lg font-bold">Select Addresses</h2>
                    <AddressSelectComponent
                        v-model="form.addresses"
                        :available-addresses="supplierAddresses"
                        :settings="settings"
                    />
                </div>

                <!-- Additional Information -->
                <div class="mb-6">
                    <h2 class="mb-2 text-lg font-bold">Additional Information</h2>
                    <div class="space-y-2">
                        <div>
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special Instructions</label>
                            <textarea
                                id="special_instructions"
                                v-model="form.special_instructions"
                                rows="3"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            ></textarea>
                        </div>
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                            <input
                                id="tax_rate"
                                v-model.number="form.tax_rate"
                                type="number"
                                step="0.01"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
                        </div>
                        <div>
                            <label for="additional_costs" class="block text-sm font-medium text-gray-700">Additional Costs</label>
                            <input
                                id="additional_costs"
                                v-model.number="form.additional_costs"
                                type="number"
                                step="0.01"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-2">
                    <Button label="Save Draft" @click="saveDraft" :loading="processing" />
                    <Button label="Submit" severity="success" @click="submit" :loading="processing" />
                    <Button label="Reset" severity="secondary" @click="reset" :disabled="processing" />
                </div>

                <!-- Error Display -->
                <div v-if="Object.keys(errors).length > 0" class="p-4 mt-4 border border-red-200 rounded-lg bg-red-50">
                    <h2 class="mb-2 text-lg font-semibold text-red-700">Errors</h2>
                    <ul class="text-red-600 list-disc list-inside">
                        <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                    </ul>
                </div>
            </ResponsiveCard>
        </Container>
    </AuthenticatedAdminLayout>
</template>
