// resources/js/Composables/usePurchaseOrderForm.js

import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

export function usePurchaseOrderForm(initialData = {}) {
    console.log('Initializing purchase order form with:', initialData);

    const toast = useToast(); // Ensure toast is initialized

    const form = ref({
        supplier_id: null,
        location_id: null,
        parts: [],
        addresses: {
            billTo: initialData?.defaultAddresses?.billTo?.[0] || null,
            shipFrom: null,
            shipTo: initialData?.defaultAddresses?.shipTo?.[0] || null,
            returnTo: null
        },
        special_instructions: '',
        tax_rate: initialData?.defaultTaxRate || 8.0,
        additional_costs: 0,
    });

    // UI State
    const loading = ref(false);
    const errors = ref({});
    const processing = ref(false);
    const selectedSupplier = ref(null);

    // Settings from backend
    const settings = computed(() => ({
        minQuantity: initialData?.settings?.minQuantity || 1,
        defaultLeadDays: initialData?.settings?.defaultLeadDays || 1,
        requireShippingAddress: initialData?.settings?.requireShippingAddress ?? true,
    }));

    // Computed Properties
    const availableSuppliers = computed(() => {
        return initialData?.availableSuppliers || [];
    });

    const supplierParts = computed(() => {
        if (!selectedSupplier.value) return [];
        const supplier = availableSuppliers.value
            .find(s => s.id === form.value.supplier_id);
        return supplier?.parts || [];
    });

    const supplierAddresses = computed(() => {
        const defaultAddresses = {
            billTo: initialData?.defaultAddresses?.billTo || [],
            shipTo: initialData?.defaultAddresses?.shipTo || [],
            shipFrom: [],
            returnTo: []
        };

        if (!selectedSupplier.value) return defaultAddresses;

        const supplier = availableSuppliers.value
            .find(s => s.id === form.value.supplier_id);

        return {
            ...defaultAddresses,
            shipFrom: supplier?.addresses?.shipFrom || [],
        };
    });

    // Form Validation
    const isValid = computed(() => {
        const hasSupplier = !!form.value.supplier_id;
        const hasBillTo = !!form.value.addresses.billTo;
        const hasShipFrom = !!form.value.addresses.shipFrom;
        const hasShipTo = !!form.value.addresses.shipTo;
        const hasParts = form.value.parts.length > 0 && form.value.parts.every(p => p.quantity_ordered > 0);

        return hasSupplier && hasBillTo && hasShipFrom && hasShipTo && hasParts;
    });

    // Cost Calculations
    const subtotal = computed(() => {
        return form.value.parts.reduce((total, part) => {
            return total + (part.quantity_ordered * part.unit_cost);
        }, 0);
    });

    const taxAmount = computed(() => {
        return (subtotal.value * form.value.tax_rate) / 100;
    });

    const totalCost = computed(() => {
        return subtotal.value + taxAmount.value + Number(form.value.additional_costs);
    });

    // Watchers
    watch(() => form.value.supplier_id, (newSupplierId) => {
        if (newSupplierId) {
            const supplier = availableSuppliers.value.find(s => s.id === newSupplierId);
            if (supplier) {
                selectedSupplier.value = supplier;
                // Update only shipFrom address, keep default billTo and shipTo
                form.value.addresses = {
                    ...form.value.addresses,
                    shipFrom: supplier.addresses?.shipFrom?.[0] || null,
                };
            } else {
                resetSupplierData();
            }
        } else {
            resetSupplierData();
        }
    });

    // Helper Functions
    function resetSupplierData() {
        selectedSupplier.value = null;
        form.value.parts = [];
        form.value.addresses = {
            billTo: initialData?.defaultAddresses?.billTo?.[0] || null,
            shipFrom: null,
            shipTo: initialData?.defaultAddresses?.shipTo?.[0] || null,
            returnTo: null
        };
    }

    function showToast(severity, summary, detail) {
        toast.add({
            severity,
            summary,
            detail,
            life: 3000
        });
    }

    // Part Management
    function addPart(part) {
        if (!part?.id) return;

        // Check if part already exists
        if (form.value.parts.some(p => p.part_id === part.id)) {
            showToast('error', 'Error', 'Part already added to order');
            return;
        }

        const purchaseTerms = part.replenishment_data?.purchaseTerms?.[0];
        if (!purchaseTerms) {
            showToast('error', 'Error', 'No pricing information available');
            return;
        }

        form.value.parts.push({
            part_id: part.id,
            quantity_ordered: settings.value.minQuantity,
            unit_cost: purchaseTerms.cost_per_part,
            total_cost: purchaseTerms.cost_per_part * settings.value.minQuantity,
            part_number: part.part_number,
            description: part.description,
            lead_days: part.replenishment_data.lead_days || settings.value.defaultLeadDays
        });
    }

    function updatePartQuantity(partId, quantity) {
        const partIndex = form.value.parts.findIndex(p => p.part_id === partId);
        if (partIndex !== -1) {
            // Part exists in form.parts; update it
            const part = form.value.parts[partIndex];
            part.quantity_ordered = Math.max(settings.value.minQuantity, quantity);
            part.total_cost = part.quantity_ordered * part.unit_cost;
        } else if (quantity >= settings.value.minQuantity) {
            // Part doesn't exist; add it if quantity meets minimum
            const availablePart = supplierParts.value.find(p => p.id === partId);
            if (availablePart) {
                const purchaseTerms = availablePart.replenishment_data?.purchaseTerms?.[0];
                if (purchaseTerms) {
                    form.value.parts.push({
                        part_id: availablePart.id,
                        quantity_ordered: quantity,
                        unit_cost: purchaseTerms.cost_per_part,
                        total_cost: quantity * purchaseTerms.cost_per_part,
                        part_number: availablePart.part_number,
                        description: availablePart.description,
                        lead_days: availablePart.replenishment_data?.lead_days || settings.value.defaultLeadDays
                    });
                }
            }
        }
        // Trigger reactivity
        form.value.parts = [...form.value.parts];
    }

    function removePart(partId) {
        form.value.parts = form.value.parts.filter(p => p.part_id !== partId);
    }

    // Form Validation
    function validateForm() {
        errors.value = {};

        if (!form.value.supplier_id) {
            errors.value.supplier_id = 'Please select a supplier';
            return false;
        }

        if (form.value.parts.length === 0) {
            errors.value.parts = 'Please add at least one part';
            return false;
        }

        if (!form.value.parts.every(p => p.quantity_ordered > 0)) {
            errors.value.parts = 'All parts must have quantity greater than 0';
            return false;
        }

        if (!form.value.addresses.billTo) {
            errors.value.billTo = 'Please select a billing address';
            return false;
        }

        if (!form.value.addresses.shipFrom) {
            errors.value.shipFrom = 'Please select a ship from address';
            return false;
        }

        if (settings.value.requireShippingAddress && !form.value.addresses.shipTo) {
            errors.value.shipTo = 'Please select a shipping address';
            return false;
        }

        return true;
    }

    // Form Submission
    async function submit() {
        console.log('Submitting form with data:', form.value);

        if (!validateForm()) {
            console.log('Form validation failed:', errors.value);
            showToast('error', 'Validation Error', 'Please check the form for errors');
            return;
        }

        processing.value = true;
        try {
            const formData = {
                ...form.value,
                total_cost: totalCost.value,
                status: 'submitted'
            };

            console.log('Sending data to server:', formData);

            await router.post(route('purchase-orders.store'), formData, {
                onSuccess: () => {
                    showToast('success', 'Success', 'Purchase order created successfully');
                    reset();
                },
                onError: (serverErrors) => {
                    console.error('Server validation errors:', serverErrors);
                    showToast('error', 'Error', 'Failed to create purchase order');
                    errors.value = serverErrors;
                }
            });
        } catch (e) {
            console.error('Form submission error:', e);
            showToast('error', 'Error', 'An unexpected error occurred');
        } finally {
            processing.value = false;
        }
    }

    async function saveDraft() {
        console.log('Saving draft with data:', form.value);

        if (!validateForm()) {
            console.log('Form validation failed:', errors.value);
            showToast('error', 'Validation Error', 'Please check the form for errors');
            return;
        }

        processing.value = true;
        try {
            const formData = {
                ...form.value,
                total_cost: totalCost.value,
                status: 'draft'
            };

            console.log('Sending draft data to server:', formData);

            await router.post(route('purchase-orders.draft'), formData, {
                onSuccess: () => {
                    showToast('success', 'Success', 'Draft saved successfully');
                    reset();
                },
                onError: (serverErrors) => {
                    console.error('Server validation errors:', serverErrors);
                    showToast('error', 'Error', 'Failed to save draft');
                    errors.value = serverErrors;
                }
            });
        } catch (e) {
            console.error('Draft save error:', e);
            showToast('error', 'Error', 'An unexpected error occurred');
        } finally {
            processing.value = false;
        }
    }

    function reset() {
        form.value = {
            supplier_id: null,
            location_id: null,
            parts: [],
            addresses: {
                billTo: initialData?.defaultAddresses?.billTo?.[0] || null,
                shipFrom: null,
                shipTo: initialData?.defaultAddresses?.shipTo?.[0] || null,
                returnTo: null
            },
            special_instructions: '',
            tax_rate: initialData.defaultTaxRate || 8.0,
            additional_costs: 0,
        };
        selectedSupplier.value = null;
        errors.value = {};
    }

    return {
        form,
        loading,
        errors,
        processing,
        selectedSupplier,
        settings,
        availableSuppliers,
        supplierParts,
        supplierAddresses,
        subtotal,
        taxAmount,
        totalCost,
        isValid,
        addPart,
        removePart,
        updatePartQuantity,
        saveDraft,
        submit,
        reset,
    };
}
