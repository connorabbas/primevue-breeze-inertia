<script setup>
import { computed } from 'vue';
import InputNumber from 'primevue/inputnumber';

const props = defineProps({
  subtotal: {
    type: Number,
    required: true
  },
  taxRate: {
    type: Number,
    required: true
  },
  additionalCosts: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['update:taxRate', 'update:additionalCosts']);

const taxAmount = computed(() => (props.subtotal * props.taxRate) / 100);
const totalCost = computed(() => props.subtotal + taxAmount.value + props.additionalCosts);

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value);
};
</script>

<template>
  <div class="bg-white rounded-lg p-6">
    <h2 class="text-xl mb-6 text-gray-800">Order Summary</h2>

    <div class="grid grid-cols-3 gap-4">
      <!-- Labels Column -->
      <div class="flex flex-col gap-4">
        <span class="text-gray-600">Subtotal</span>
        <span class="text-gray-600">Tax Rate (%)</span>
        <span class="text-gray-600">Tax Amount</span>
        <span class="text-gray-600">Additional Costs</span>
        <span class="font-semibold pt-4 border-t border-gray-200">Total</span>
      </div>

      <!-- Values Column (spanning 2 columns) -->
      <div class="col-span-2 flex flex-col gap-4">
        <span class="text-right">{{ formatCurrency(subtotal) }}</span>
        <InputNumber
          :model-value="taxRate"
          @update:model-value="$emit('update:taxRate', $event)"
          :min-fraction-digits="2"
          :max-fraction-digits="2"
          :use-grouping="false"
          class="w-full"
        />
        <span class="text-right">{{ formatCurrency(taxAmount) }}</span>
        <InputNumber
          :model-value="additionalCosts"
          @update:model-value="$emit('update:additionalCosts', $event)"
          mode="currency"
          currency="USD"
          class="w-full"
        />
        <span class="text-right font-semibold pt-4 border-t border-gray-200">
          {{ formatCurrency(totalCost) }}
        </span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.p-inputnumber {
  display: flex;
  justify-content: flex-end;
}

.p-inputnumber-input {
  width: 8rem !important;
  text-align: right;
}

.p-inputtext:enabled:focus {
  box-shadow: none;
  border-color: #818cf8;
  outline: none;
}
</style>
