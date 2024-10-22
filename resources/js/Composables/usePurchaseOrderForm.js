import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

export function usePurchaseOrderForm() {
    const toast = useToast();
    const page = usePage();

    // Form state
    const form = ref({
        supplier_id: null,
        location_id: null,
        parts: [],
        bill_to_address_index: 0,
        ship_from_address_index: 0,
        ship_to_address_index: 0,
    });

    // Selected entities
    const selectedSupplier = ref(null);
    const selectedLocation = ref(null);

    // Additional state
    const loading = ref(false);
    const errors = ref({});
    const processing = ref(false);

    // Computed values for totals
    const subtotal = computed(() => {
        return form.value.parts.reduce((acc, part) => {
            return acc + (part.quantity_ordered * part.unit_cost);
        }, 0);
    });

    const total = computed(() => {
        return subtotal.value;
    });

    // Methods for supplier selection
    async function loadSupplier(id) {
        loading.value = true;
        try {
            const response = await router.get(route('api.suppliers.show', id), {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (response) => {
                    selectedSupplier.value = response.data;
                    form.value.supplier_id = id;
                }
            });
        } catch (e) {
            showError('Error loading supplier');
        } finally {
            loading.value = false;
        }
    }

    // Methods for part management
    function addPart(part) {
        const exists = form.value.parts.find(p => p.id === part.id);
        if (exists) {
            showError('Part already added to order');
            return;
        }

        form.value.parts.push({
            part_id: part.id,
            quantity_ordered: 1,
            unit_cost: part.replenishment_data.purchaseTerms[0].cost_per_part,
            total_cost: part.replenishment_data.purchaseTerms[0].cost_per_part
        });
    }

    function removePart(partId) {
        form.value.parts = form.value.parts.filter(p => p.part_id !== partId);
    }

    function updatePartQuantity(partId, quantity) {
        const part = form.value.parts.find(p => p.part_id === partId);
        if (part) {
            part.quantity_ordered = quantity;
            part.total_cost = quantity * part.unit_cost;
        }
    }

    // Form submission methods
    async function saveDraft() {
        processing.value = true;
        try {
            await router.post(route('purchase-orders.draft'), form.value, {
                onSuccess: () => {
                    showSuccess('Purchase order draft saved');
                    reset();
                },
                onError: (errors) => {
                    showError('Error saving draft');
                    errors.value = errors;
                }
            });
        } finally {
            processing.value = false;
        }
    }

    async function submit() {
        processing.value = true;
        try {
            await router.post(route('purchase-orders.store'), form.value, {
                onSuccess: () => {
                    showSuccess('Purchase order created successfully');
                    reset();
                },
                onError: (errors) => {
                    showError('Error creating purchase order');
                    errors.value = errors;
                }
            });
        } finally {
            processing.value = false;
        }
    }

    // Helper methods
    function reset() {
        form.value = {
            supplier_id: null,
            location_id: null,
            parts: [],
            bill_to_address_index: 0,
            ship_from_address_index: 0,
            ship_to_address_index: 0
        };
        selectedSupplier.value = null;
        selectedLocation.value = null;
        errors.value = {};
    }

    // Toast notifications
    function showSuccess(message) {
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: message,
            life: 3000
        });
    }

    function showError(message) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: message,
            life: 3000
        });
    }

    return {
        // State
        form,
        loading,
        errors,
        processing,
        selectedSupplier,
        selectedLocation,

        // Computed
        subtotal,
        total,

        // Methods
        loadSupplier,
        addPart,
        removePart,
        updatePartQuantity,
        saveDraft,
        submit,
        reset,
    };
}
