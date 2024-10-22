<script setup>
import { ref, onMounted } from 'vue';
import Button from 'primevue/button';

const props = defineProps({
    initialData: {
        type: Object,
        required: true
    }
});

// Local state management
const rawData = ref(props.initialData);
const selectedSupplierId = ref(null);

onMounted(() => {
    console.log('Debug Component Mounted');
    console.log('Initial Data:', props.initialData);
    if (props.initialData?.availableSuppliers) {
        console.log('Suppliers Count:', props.initialData.availableSuppliers.length);
    }
});

// Simple supplier selection
function selectSupplier(id) {
    selectedSupplierId.value = id;
    console.log('Selected Supplier:', id);
}
</script>

<template>
    <div class="p-4 space-y-4">
        <!-- Raw Data Display -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h2 class="text-lg font-bold mb-2">Raw Data</h2>
            <div class="text-sm mb-2">
                Suppliers Count: {{ rawData.availableSuppliers?.length || 0 }}
            </div>
            <pre class="text-xs overflow-auto max-h-40">{{ JSON.stringify(rawData, null, 2) }}</pre>
        </div>

        <!-- Supplier List -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-bold mb-2">Available Suppliers</h2>
            <div v-if="rawData.availableSuppliers?.length > 0" class="grid gap-2">
                <div v-for="supplier in rawData.availableSuppliers"
                     :key="supplier.id"
                     class="border p-2 rounded">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ supplier.name }}</div>
                            <div class="text-sm text-gray-500">{{ supplier.account_number }}</div>
                        </div>
                        <Button
                            :label="selectedSupplierId === supplier.id ? 'Selected' : 'Select'"
                            :severity="selectedSupplierId === supplier.id ? 'success' : 'secondary'"
                            @click="selectSupplier(supplier.id)"
                        />
                    </div>
                </div>
            </div>
            <div v-else class="text-gray-500 italic">
                No suppliers available
            </div>
        </div>

        <!-- Selected Supplier Details -->
        <div v-if="selectedSupplierId" class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-bold mb-2">Selected Supplier Details</h2>
            <div v-if="rawData.availableSuppliers">
                <pre class="text-xs">{{ JSON.stringify(
                    rawData.availableSuppliers.find(s => s.id === selectedSupplierId),
                    null, 2
                ) }}</pre>
            </div>
        </div>
    </div>
</template>
