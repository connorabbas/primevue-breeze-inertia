<script setup>
import { computed } from 'vue';
import Select from 'primevue/select';

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

const formattedSuppliers = computed(() => {
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

        <label for="supplier" class="block text-sm font-medium text-gray-700">
            Select Supplier
        </label>
        <Select
            id="supplier"
            v-model="selectedValue"
            :options="formattedSuppliers"
            optionLabel="label"
            optionValue="value"
            :loading="loading"
            class="w-full"
            placeholder="Select a supplier"
        >
            <template #option="{ option }">
                <div class="flex flex-col">
                    <span class="font-medium">{{ option.name }}</span>
                    <span class="text-sm text-gray-500">{{ option.account_number }}</span>
                </div>
            </template>
        </Select>
    </div>
</template>
