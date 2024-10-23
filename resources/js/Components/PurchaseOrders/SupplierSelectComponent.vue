
<script setup>
import { computed,ref } from 'vue';
import Select from 'primevue/select';

const getSupplierTooltip = (supplier) => {
    return {
        content: `Name: ${supplier.name}\nAccount: ${supplier.account_number}\nAddress: ${supplier.address}\nContact: ${supplier.contact}`,
        class: 'supplier-tooltip'
    };
};

const props = defineProps({
    modelValue: {
        type: [Number, null],
        required: true
    },
    suppliers: {
        type: Array,
        required: true,
        default: () => []
    },
    loading: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

// Debug mode computed
const isDebug = ref(process.env.NODE_ENV === 'development');

const formattedSuppliers = computed(() => {
    console.log('Formatting suppliers:', props.suppliers);
    return props.suppliers.map(supplier => ({
        label: `${supplier.name} (${supplier.account_number})`,
        value: supplier.id,
        ...supplier
    }));
});

const selectedValue = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});
</script>
<template>
    <div>
        <Select
            id="supplier"
            v-model="selectedValue"
            :options="formattedSuppliers"
            optionLabel="label"
            optionValue="value"
            placeholder="Select a supplier"
            class="w-full"
            :loading="loading"
        >
            <!-- Add tooltip template -->
            <template #option="{ option }">
                <div
                    class="supplier-option"
                    v-tooltip.right="getSupplierTooltip(option)"
                >
                    {{ option.label }}
                </div>
            </template>
        </Select>
    </div>
</template>
<style>.supplier-tooltip.p-tooltip .p-tooltip-text {
    white-space: pre-line;
    font-size: 0.875rem;
    padding: 0.5rem;
}</style>
